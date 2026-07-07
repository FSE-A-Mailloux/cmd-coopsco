<?php
require_once('./vendor/autoload.php');
require_once('./phpClasses/class.FusionJsonHtml.php');
require_once('./phpClasses/class.oauth2.php');

use Spipu\Html2Pdf\Html2Pdf;

global $tabWebServices;
$tabWebServices['fseCmdManage'] = [true]; // [accredité]

function ws_fseCmdManage($request)
{
  // récupération des variables globale
  global $globalData;
  global $pConfig;

  // selon le contexte
  if($globalData['context'] == 'new'){
    // insérer en premier la comande et récupérer l'identifiant
    $SQLrequete = "insert into \${prefixe}fse_commande(id_ann, mail_cmd, tel_cmd, nom_cmd, prenom_cmd, nbrenf_cmd, dtcre_cmd, num_cmd, id_uti)
select id_ann, :mail_cmd, :tel_cmd, :nom_cmd, :prenom_cmd, :nbrenf_cmd, :dtcre_cmd, '-', :id_uti
from \${prefixe}fse_annee
where encours_ann = 1";
    $SQLparams = Array(
        ':mail_cmd' => $globalData['params']['fse_commande']['mail_cmd'],
        ':tel_cmd' => $globalData['params']['fse_commande']['tel_cmd'],
        ':nom_cmd' => $globalData['params']['fse_commande']['nom_cmd'],
        ':prenom_cmd' => $globalData['params']['fse_commande']['prenom_cmd'],
        ':nbrenf_cmd' => $globalData['params']['fse_commande']['nbrenf_cmd'],
        ':dtcre_cmd' => date("Y-m-d H:i:s"),
        ':id_uti' => $globalData['utilisateur']['id_uti']
    );
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // récupéré id
    $idCmd = $globalData['db']->getLastId();
    $globalData['params']['fse_commande']['id_cmd'] = $idCmd;

    // ajouter les enfants
    $select = "";
    foreach( $globalData['params']['fse_commande']['descenf_cmd'] as $idx => $enfant ){
      if($select != ""){
        $select .= " union ";
      }
      $select .= 'select '.$idCmd.', \''.str_replace("'","''",$enfant['nom']).'\', \''.str_replace("'","''",$enfant['prenom']).'\', \''.str_replace("'","''",$enfant['classe']). '\' from dual';
      
    }

    $SQLrequete = "insert into \${prefixe}fse_cmdenf(id_cmd, nom_cmf, prenom_cmf, classe_cmf) ".$select;
    $SQLparams = Array();
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $globalData['params']['fse_commande']['dtcre_cmd'] = date("d/m/Y");

    // créer la requête d'insertion des articles
    $articles = array();
    $total = 0.0;
    $select = "";
    foreach( $globalData['params']['fse_commande']['articles'] as $idx => $article ){
      if($article['nbre_art'] != null && $article['nbre_art'] > 0 ){
        if($select != ""){
          $select .= " union ";
        }
        $select .= 'select '.str_replace("'","''",$idCmd).', '.str_replace("'","''",$article['id_art']).', '.str_replace("'","''",$article['nbre_art']).', '.str_replace("'","''",$article['prix_pafe'] - $article['reduction']). ' from dual';
        $total += ($article['nbre_art'] * ($article['prix_pafe'] - $article['reduction']));
        array_push($articles, Array('ordre_art'=>$article['ordre_art'],
            'lib_art'=>$article['lib_art'],
            'code_art'=>$article['code_art'],
            'marque_art'=>$article['marque_art'],
            'nbre_art'=>$article['nbre_art'],
            'prix_pafe_str'=>number_format((float)($article['prix_pafe'] - $article['reduction']), 2, '.', ''). ' €',//$article['prix_pafe_str'],
            'prix_tot_str'=>number_format((float)($article['nbre_art'] * ($article['prix_pafe'] - $article['reduction'])), 2, '.', ''). ' €'
            ));
      }
    }

    $globalData['params']['fse_commande']['articles_pdf'] = $articles;
    $globalData['params']['fse_commande']['total'] = number_format((float)$total, 2, '.', ''). ' €';

    $SQLrequete = "insert into \${prefixe}fse_artcmd(id_cmd, id_art, nbr_acd, prix_acd) ".$select;
    $SQLparams = Array();
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // récupérer le numéro de commande
    $SQLrequete = "select \${prefixe}fse_annee.id_ann, lib_ann, dtbutoir_ann, prefix_ann
from \${prefixe}fse_commande, \${prefixe}fse_annee
where id_cmd = :id_cmd
and \${prefixe}fse_annee.id_ann = \${prefixe}fse_commande.id_ann";
    $SQLparams = Array(':id_cmd' => $idCmd);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    $numCmd = sprintf('%s%06d', $tabResult[0]['prefix_ann'], $idCmd);
        
    // ajouter les données nécessaires à la construction du PDF
    $globalData['params']['fse_commande']['num_cmd'] = $numCmd;
    $globalData['params']['fse_commande']['lib_ann'] = $tabResult[0]['lib_ann'];
    
    $timestamp = strtotime($tabResult[0]['dtbutoir_ann']);
    $globalData['params']['fse_commande']['dtbutoir_ann'] = str_replace(' 01 ', ' 1er ', strftime('%A %d %B %Y', $timestamp));

    // Mettre a jour le numéro de commande
    $SQLrequete = "update \${prefixe}fse_commande set num_cmd = :num_cmd where id_cmd = :id_cmd";
    $SQLparams = Array(':id_cmd' => $idCmd, ':num_cmd' => $numCmd);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // récupérer le modèle HTML pour la fusion
    $SQLrequete = "select modele_mhl
from \${prefixe}modelehtml
where code_mhl = 'PDF_CMD_FSE'";
    $SQLparams = Array();
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // si il n'existe pas
    if(count($tabResult) != 1)
    {
      $globalData['errCode'] = "ERR_GEN_02";
      $globalData['errLib'] = "Le modèle HTML du PDF de commande est introuvable";
      throw new Exception('ERREUR_PDF');
    }

    // réaliser la fusion HTML
    $dataFusion = array_merge($globalData['params']['fse_commande'], $pConfig);
    //$fusion = new FusionJsonHtml($globalData['params']['fse_commande'], $tabResult[0]['modele_mhl']);
    $fusion = new FusionJsonHtml($dataFusion, $tabResult[0]['modele_mhl']);
    $result = $fusion->process();
    
    // Générer le PDF
    $nomPdf = "commande_coopSco_" .$numCmd. ".pdf";
    $html2pdf = new Html2Pdf();
    $html2pdf->writeHTML($result);
    $pdfString = $html2pdf->output($nomPdf,'S');

    $globalData['message']['response']['pdfBase64'] = base64_encode($pdfString);
    $globalData['message']['response']['nomPdf'] = $nomPdf;
    
    $globalData['message']['response']['num_cmd'] = $numCmd;
    $globalData['message']['response']['dtbutoir_ann'] = $globalData['params']['fse_commande']['dtbutoir_ann'];
    
    // selon le mode de gestion
    if(!$globalData['params']['fse_commande']['gest'] || $globalData['params']['fse_commande']['mail_gest'] ){

      // récupérer le modèle HTML pour le mail
      $SQLrequete = "select modele_mhl
from \${prefixe}modelehtml
where code_mhl = 'MAIL_CMD_FSE'";
      $SQLparams = Array();
      $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

      // si il n'existe pas
      if(count($tabResult) != 1)
      {
        $globalData['errCode'] = "ERR_GEN_02";
        $globalData['errLib'] = "Le modèle HTML du mail de commande est introuvable";
        throw new Exception('ERREUR_PDF');
      }
      // réaliser la fusion HTML
      $fusion = new FusionJsonHtml($dataFusion, $tabResult[0]['modele_mhl']);
      $htmlBody = $fusion->process();

      // envoyer le mail
      envoyerMail($globalData['params']['fse_commande']['mail_cmd'], "FSE - CoopSco - Confirmation commande", $htmlBody, array($nomPdf => $pdfString));

    }
    
  }else if($globalData['context'] == 'regler'){
    // Récupérer la commande
    $SQLrequete = "select * from \${prefixe}fse_commande where id_cmd = :id_cmd";
    $SQLparams = Array(':id_cmd' => $globalData['params']['fse_commande']['id_cmd']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    if(count($tabResult) != 1)
    {
      $globalData['errCode'] = "ERR_CMD_01";
      $globalData['errLib'] = "La commande est introuvable";
      throw new Exception('ERREUR_CMD');
    }
    $commande = $tabResult[0];
    
    // Parcourir la liste des règlements
    foreach( $globalData['params']['fse_commande']['reglement'] as $idx => $reglement ){
      // Si le règlement existe déjà
      if($reglement['id_cmdrglt'] != null ){
        if($reglement['dateannul_cmdrglt'] == null || $reglement['dateannul_cmdrglt'] == '-'){
          // Mettre à jour le règlement
          $SQLrequete = "update \${prefixe}fse_cmdrglt
set dateannul_cmdrglt = :dateannul_cmdrglt
where id_cmd = :id_cmd
and id_cmdrglt = :id_cmdrglt";
          $SQLparams = Array(
              ':id_cmd' => $globalData['params']['fse_commande']['id_cmd'],
              ':id_cmdrglt' => $reglement['id_cmdrglt'],
              ':dateannul_cmdrglt' => ($reglement['dateannul_cmdrglt'] == '-' ? date("Y-m-d H:i:s") : null)
          );
        }
      }else{
        // Si le montant est inférieur ou égale à 0
        if($reglement['montant_cmdrglt'] <= 0){
          $globalData['errCode'] = "ERR_CMDRGLT_02";
          $globalData['errLib'] = "Le montant du règlement doit être supérieur à 0";
          throw new Exception('ERREUR_FCN');
        }
        
        // Si le type de règlement est chèque, et que l'Id_bqe n'est pas transmis
        if($reglement['type_cmdrglt'] == 1){
          if($reglement['id_bqe'] == null ){
            // Si le nom de la banque n'est pas transmis
            if($reglement['nom_bqe'] == "" || $reglement['chequenomporteur_cmd_rglt'] == ""){
              $globalData['errCode'] = "ERR_CMDRGLT_01";
              $globalData['errLib'] = "Le nom de la banque et du porteur doivent être renseignés";
              throw new Exception('ERREUR_FCN');
            }

            // Rechercher le nom de la banque dans la base de données
            $SQLrequete = "select id_bqe from \${prefixe}fse_banque where upper(nom_bqe) = upper(:nom_bqe)";
            $SQLparams = Array(':nom_bqe' => $reglement['nom_bqe']);
            $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
            if(count($tabResult) > 0)
            {
              $reglement['id_bqe'] = $tabResult[0]["id_bqe"];
            }else{

              // Ajouter la banque
              $SQLrequete = "insert into \${prefixe}fse_banque ( nom_bqe)
  values(:nom_bqe)";
              $SQLparams = Array(
                  ':nom_bqe' => $reglement['nom_bqe']
              );
              $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
              $reglement['id_bqe'] = $globalData['db']->getLastId();

            }
          }else{
            if($reglement['chequenomporteur_cmd_rglt'] == ""){
              $globalData['errCode'] = "ERR_CMDRGLT_03";
              $globalData['errLib'] = "Le nom de la banque et du porteur doivent être renseignés";
              throw new Exception('ERREUR_FCN');
            }
          }
          
        }
        
        // ajouter le règlement
        $SQLrequete = "insert into \${prefixe}fse_cmdrglt (id_cmd, type_cmdrglt, montant_cmdrglt, date_cmdrglt, chequenomporteur_cmd_rglt, id_bqe)
values(:id_cmd, :type_cmdrglt, :montant_cmdrglt, :date_cmdrglt, :chequenomporteur_cmd_rglt, :id_bqe)";
        $SQLparams = Array(
            ':id_cmd' => $globalData['params']['fse_commande']['id_cmd'],
            ':type_cmdrglt' => $reglement['type_cmdrglt'],
            ':montant_cmdrglt' => $reglement['montant_cmdrglt'],
            ':date_cmdrglt' => date("Y-m-d H:i:s"),
            ':chequenomporteur_cmd_rglt' => ($reglement['type_cmdrglt'] == 1 ? $reglement['chequenomporteur_cmd_rglt'] : null),
            ':id_bqe' => ($reglement['type_cmdrglt'] == 1 ? $reglement['id_bqe'] : null)
        );
        
      }
      
      // exécuter la requête
      $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    }
    
    // si la somme des règlements est à 0, invalider la commande
    $SQLrequete = "update \${prefixe}fse_commande
set dtvalid_cmd = null
where id_cmd = :id_cmd
and (select ifnull(sum(montant_cmdrglt),0) from \${prefixe}fse_cmdrglt where id_cmd = :id_cmd and dateannul_cmdrglt is null) = 0";
    $SQLparams = Array(':id_cmd' => $globalData['params']['fse_commande']['id_cmd']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // Mettre a jour la date de validation
    if($globalData['params']['fse_commande']['validation'] == '1' && $globalData['params']['fse_commande']['dtvalid_cmd'] == ''){
      $SQLrequete = "update \${prefixe}fse_commande set dtvalid_cmd = :dtvalid_cmd where id_cmd = :id_cmd";
      $SQLparams = Array(':id_cmd' => $globalData['params']['fse_commande']['id_cmd'], ':dtvalid_cmd' => date("Y-m-d H:i:s"));
      $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

      // récupérer le modèle HTML pour le mail
      $SQLrequete = "select modele_mhl
  from \${prefixe}modelehtml
  where code_mhl = 'MAIL_VALID_CMD_FSE'";
      $SQLparams = Array();
      $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

      // si il n'existe pas
      if(count($tabResult) != 1)
      {
        $globalData['errCode'] = "ERR_GEN_02";
        $globalData['errLib'] = "Le modèle HTML du mail de validation est introuvable";
        throw new Exception('ERREUR_PDF');
      }

      // réaliser la fusion HTML
      $dataFusion = array_merge($commande, $pConfig);
      $fusion = new FusionJsonHtml($dataFusion, $tabResult[0]['modele_mhl']);
      $htmlBody = $fusion->process();

      // envoyer le mail
      envoyerMail($commande['mail_cmd'], "FSE - CoopSco - Validation commande", $htmlBody);
    }
  }else if($globalData['context'] == 'pdf' || $globalData['context'] == 'pdfUti' || $globalData['context'] == 'facture'){
    // Préparer le flux de données nécessaire à la création du pdf
    // données de la commande
    if($globalData['context'] == 'pdf' || $globalData['context'] == 'facture'){
      $SQLrequete = "select \${prefixe}fse_commande.*, dtbutoir_ann, lib_ann
from \${prefixe}fse_commande, \${prefixe}fse_annee
where id_cmd = :id_cmd
and \${prefixe}fse_annee.id_ann = \${prefixe}fse_commande.id_ann";
      $SQLparams = Array(':id_cmd' => $globalData['params']['fse_commande']['id_cmd']);
      
    }else if($globalData['context'] == 'pdfUti'){
      $SQLrequete = "select \${prefixe}fse_commande.*, dtbutoir_ann, lib_ann
from \${prefixe}fse_commande, \${prefixe}fse_annee
where id_uti = :id_uti
and id_cmd = :id_cmd
and \${prefixe}fse_annee.id_ann = \${prefixe}fse_commande.id_ann";
      $SQLparams = Array(':id_cmd' => $globalData['params']['fse_commande']['id_cmd'], ':id_uti' => $globalData['utilisateur']['id_uti']);
      
    }
    
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    if(count($tabResult) != 1)
    {
      $globalData['errCode'] = "ERR_CMD_01";
      $globalData['errLib'] = "La commande est introuvable";
      throw new Exception('ERREUR_CMD');
    }
    $globalData['params']['fse_commande'] = $tabResult[0];
    
    $timestamp = strtotime($tabResult[0]['dtbutoir_ann']);
    $globalData['params']['fse_commande']['dtbutoir_ann'] = str_replace(' 01 ', ' 1er ', strftime('%A %d %B %Y', $timestamp));
    $timestamp = strtotime($tabResult[0]['dtcre_cmd']);
    $globalData['params']['fse_commande']['dtcre_cmd'] = strftime('%d/%m/%Y', $timestamp);
    
    // liste des enfants
    $SQLrequete = "SELECT nom_cmf as nom, prenom_cmf as prenom, classe_cmf as classe
FROM \${prefixe}fse_cmdenf
WHERE id_cmd = :id_cmd";
    $SQLparams = Array(':id_cmd' => $globalData['params']['fse_commande']['id_cmd']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $globalData['params']['fse_commande']['descenf_cmd'] = $tabResult;
    
    // liste des articles
    $SQLrequete = "SELECT \${prefixe}fse_article.*, nbr_acd, prix_acd
FROM \${prefixe}fse_artcmd, \${prefixe}fse_article
WHERE \${prefixe}fse_artcmd.id_cmd = :id_cmd
and \${prefixe}fse_article.id_art = \${prefixe}fse_artcmd.id_art
order by ordre_art";
    $SQLparams = Array(':id_cmd' => $globalData['params']['fse_commande']['id_cmd']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // créer la liste des articles
    $articles = array();
    $total = 0.0;
    $select = "";
    foreach( $tabResult as $idx => $article ){
      $total += ($article['nbr_acd'] * $article['prix_acd']);
      array_push($articles, Array('ordre_art'=>$article['ordre_art'],
          'lib_art'=>$article['lib_art'],
          'code_art'=>$article['code_art'],
          'marque_art'=>$article['marque_art'],
          'nbre_art'=>$article['nbr_acd'],
          'prix_pafe_str'=>$article['prix_acd'],
          'prix_tot_str'=>number_format((float)($article['nbr_acd'] * $article['prix_acd']), 2, '.', ''). ' €'
      ));
    }
    
    $globalData['params']['fse_commande']['articles_pdf'] = $articles;
    $globalData['params']['fse_commande']['total'] = number_format((float)$total, 2, '.', ''). ' €';


    // récupérer le modèle HTML pour la fusion
    if($globalData['context'] == 'facture'){
      $SQLrequete = "select modele_mhl
  from \${prefixe}modelehtml
  where code_mhl = 'PDF_FACTURE_FSE'";
    }else{
      $SQLrequete = "select modele_mhl
  from \${prefixe}modelehtml
  where code_mhl = 'PDF_CMD_FSE'";
    }
    $SQLparams = Array();
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // si il n'existe pas
    if(count($tabResult) != 1)
    {
      $globalData['errCode'] = "ERR_GEN_02";
      $globalData['errLib'] = "Le modèle HTML du PDF de commande est introuvable";
      throw new Exception('ERREUR_PDF');
    }

    // réaliser la fusion HTML
    $dataFusion = array_merge($globalData['params']['fse_commande'], $pConfig);
    $fusion = new FusionJsonHtml($dataFusion, $tabResult[0]['modele_mhl']);
    $result = $fusion->process();
    
    // Générer le PDF
    if($globalData['context'] == 'facture'){
      $nomPdf = "facture_coopSco_F" .$globalData['params']['fse_commande']['num_cmd']. ".pdf";
    }else{
      $nomPdf = "commande_coopSco_" .$globalData['params']['fse_commande']['num_cmd']. ".pdf";
    }
    $html2pdf = new Html2Pdf();
    $html2pdf->writeHTML($result);
    $pdfString = $html2pdf->output($nomPdf,'S');

    $globalData['message']['response']['pdfBase64'] = base64_encode($pdfString);
    $globalData['message']['response']['nomPdf'] = $nomPdf;
  
  }else if($globalData['context'] == 'annulrglt'){

    // Mettre à jour le montant du règlement
    $SQLrequete = "update \${prefixe}fse_commande set mtregle_cmd = 0, dtvalid_cmd = null where id_cmd = :id_cmd";
    $SQLparams = Array(':id_cmd' => $globalData['params']['fse_commande']['id_cmd']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
  }else if($globalData['context'] == 'supprcotis'){

    // Mettre à jour le montant du règlement
    $SQLrequete = "DELETE
FROM \${prefixe}fse_artcmd
WHERE \${prefixe}fse_artcmd.id_cmd = :id_cmd
AND \${prefixe}fse_artcmd.id_art = (SELECT \${prefixe}fse_article.id_art FROM \${prefixe}fse_article WHERE \${prefixe}fse_article.code_art = 'COTIS')";
    $SQLparams = Array(':id_cmd' => $globalData['params']['fse_commande']['id_cmd']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

  }else if($globalData['context'] == 'annulcmd'){
    // Mettre à jour le flag d'annulation
    $SQLrequete = "update \${prefixe}fse_commande set annul_cmd = 1 where id_cmd = :id_cmd";
    $SQLparams = Array(':id_cmd' => $globalData['params']['fse_commande']['id_cmd']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
  }else if($globalData['context'] == 'lier'){
    // lier les commandes à l'utilisateur courant
    $SQLrequete = "update \${prefixe}fse_commande
set id_uti = :id_uti
where id_uti is null
and annul_cmd = 0
and mail_cmd = :mail_uti";

    $SQLparams = Array(':id_uti' => $globalData['utilisateur']['id_uti'], ':mail_uti' => $globalData['utilisateur']['mail_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  
    
  }else if($globalData['context'] == 'relance'){
    
    // liste des articles
    $SQLrequete = "select \${prefixe}fse_commande.id_cmd
, \${prefixe}fse_commande.num_cmd
, \${prefixe}fse_commande.mail_cmd
, DATE_FORMAT(\${prefixe}fse_commande.dtcre_cmd, \"%d/%m/%Y\") as dtcre_cmd
, concat(cast(format((sum(\${prefixe}fse_artcmd.nbr_acd * \${prefixe}fse_artcmd.prix_acd) - IFNULL(mtregle_cmd, 0)), 2) as char(20)), ' €') as solde_cmd
from \${prefixe}fse_commande
inner join \${prefixe}fse_artcmd on \${prefixe}fse_artcmd.id_cmd = \${prefixe}fse_commande.id_cmd
where \${prefixe}fse_commande.id_cmd = :id_cmd
group by \${prefixe}fse_commande.id_cmd, \${prefixe}fse_commande.num_cmd, \${prefixe}fse_commande.mtregle_cmd, \${prefixe}fse_commande.dtcre_cmd";
    $SQLparams = Array(':id_cmd' => $globalData['params']['fse_commande']['id_cmd']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
        
    // si il n'existe pas
    if(count($tabResult) != 1)
    {
      $globalData['errCode'] = "ERR_GEN_02";
      $globalData['errLib'] = "La commande est introuvable";
      throw new Exception('ERREUR');
    }
      
    $commande = $tabResult[0];

    // récupérer le modèle HTML pour le mail
    $SQLrequete = "select modele_mhl
from \${prefixe}modelehtml
where code_mhl = 'MAIL_FSE_RELANCE_CMD'";
    $SQLparams = Array();
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // si il n'existe pas
    if(count($tabResult) != 1)
    {
      $globalData['errCode'] = "ERR_GEN_02";
      $globalData['errLib'] = "Le modèle HTML du mail de relance est introuvable";
      throw new Exception('ERREUR_PDF');
    }

    // réaliser la fusion HTML
    $dataFusion = array_merge($commande, $pConfig);
    $fusion = new FusionJsonHtml($dataFusion, $tabResult[0]['modele_mhl']);
    $htmlBody = $fusion->process();

    // envoyer le mail
    envoyerMail($commande['mail_cmd'], "FSE - CoopSco - Relance commande", $htmlBody);

  }else if($globalData['context'] == 'cbstart'){

    $id_cmd = intval($globalData['params']['fse_commande']['id_cmd']);
    
    // Récupérer les données de la commande
    $SQLrequete = "select TabCommande.*
, tab_totalcmd.total_cmd - IFNULL(tab_totalrglt.rglt_total, 0) as solde_cmd
, case when TabCommande.dtvalid_cmd is null then 0 else 1 end as cmd_valid
, case when TabCommande.CbCheckoutDateHeure is null then 1
  when TabCommande.CbCheckoutDateHeure < NOW() then 1
  else 0
  end as CbCheckoutDateHeureDepasse
from \${prefixe}fse_commande as TabCommande
left join (
  select TabArticle.id_cmd
  , sum(TabArticle.nbr_acd * TabArticle.prix_acd) as total_cmd
  from \${prefixe}fse_artcmd as TabArticle
  group by TabArticle.id_cmd
) as tab_totalcmd on tab_totalcmd.id_cmd = TabCommande.id_cmd
left join (
  select TabReglement.id_cmd,
  sum(case 
   when TabReglement.dateannul_cmdrglt is null
   then TabReglement.montant_cmdrglt
   else 0
   end) as rglt_total
  from \${prefixe}fse_cmdrglt as TabReglement
  group by TabReglement.id_cmd
) as tab_totalrglt on tab_totalrglt.id_cmd = TabCommande.id_cmd
where TabCommande.id_cmd = :id_cmd";
    $SQLparams = Array(':id_cmd' => $id_cmd);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    
    
    // si il n'existe pas
    if(count($tabResult) !== 1)
    {
      $globalData['errCode'] = "ERR_CMDCB_01";
      $globalData['errLib'] = "La commande est introuvable";
      $globalData['message']['response']['code'] = "ERREUR";
      throw new Exception('ERREUR');
      
    }elseif($tabResult[0]['annul_cmd'] === 1 ){
      $globalData['message']['response']['code'] = "ANNUL";
      
    }elseif($tabResult[0]['cmd_valid'] === 1 ){
      $globalData['message']['response']['code'] = "VALID";
      
    }elseif($tabResult[0]['solde_cmd'] === 1 ){
      $globalData['message']['response']['code'] = "SOLDE";
      
    }elseif($tabResult[0]['CbCheckoutId'] === null ){
      $globalData['message']['response']['code'] = "URL";
      
      $apiOauth2 = new Oauth2('HELLOASSO');
      $params = $apiOauth2->getParametre();
      $endpoint = "/organizations/".$params['asso-slug']."/checkout-intents";
      $dataApi =[
          'totalAmount' => round($tabResult[0]['solde_cmd'] * 100),
          'initialAmount' => round($tabResult[0]['solde_cmd'] * 100),
          'itemName' => 'Commande N°'.$tabResult[0]['num_cmd'].' CoopSco Auguste Mailloux',
          'backUrl' => $pConfig['site_url']."/index.html?action=paiementcb&contexte=abandon",
          'errorUrl' => $pConfig['site_url']."/index.html?action=paiementcb&contexte=ko",
          'returnUrl' => $pConfig['site_url']."/index.html?action=paiementcb&contexte=ok",
          'containsDonation' => false,
          'payer' => [
              'firstName' => $tabResult[0]['prenom_cmd'],
              'lastName' => $tabResult[0]['nom_cmd'],
              'email' => $tabResult[0]['mail_cmd']
          ],
          'metadata' => Array(
              'id_cmd' => $id_cmd
          )
      ];
      $response = $apiOauth2->callApi("POST", $endpoint, $dataApi);
      
      $checkoutId = $response['id'];
      $checkoutUrl = $response['redirectUrl'];
      
      // enregistrer 
      $SQLrequete = "update \${prefixe}fse_commande
set CbCheckoutId = :CbCheckoutId
, CbCheckoutDateHeure = DATE_ADD(NOW(), INTERVAL 14 minute)
where id_cmd = :id_cmd";
      $SQLparams = Array(':id_cmd' => $id_cmd,
          ':CbCheckoutId' => $checkoutId);
      $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
      $globalData['message']['response']['url'] = $checkoutUrl;
      
    }else{
      $CbCheckoutDateHeureDepasse = $tabResult[0]['CbCheckoutDateHeureDepasse'];
      $montant100 = round($tabResult[0]['solde_cmd'] * 100);
      $num_cmd = $tabResult[0]['num_cmd'];
      $firstName = $tabResult[0]['prenom_cmd'];
      $lastName = $tabResult[0]['nom_cmd'];
      $email = $tabResult[0]['mail_cmd'];
      
      $apiOauth2 = new Oauth2('HELLOASSO');
      $params = $apiOauth2->getParametre();
      $endpoint = "/organizations/".$params['asso-slug']."/checkout-intents/".$tabResult[0]['CbCheckoutId']."?withFailedRefundOperation=false";
      $response = $apiOauth2->callApi("GET", $endpoint, Array());
    
      //$globalData['message']['response']['callApi'] = $response;
      
      $CbCheckOutId = $response['id'];
      $checkoutUrl = $response['redirectUrl'];
      $id_cmd_cb = $response['metadata']['id_cmd'];
      
      if(intval($id_cmd_cb) !== $id_cmd){
        $globalData['errCode'] = "ERR_CMDCB_02";
        $globalData['errLib'] = "Incohérence dans les identifiants de commande !!";
        $globalData['message']['response']['code'] = "ERREUR";
        throw new Exception('ERREUR_SEC');
      }
      
      // vérifier si un paiement a déjà été effectué
      $paiementFait = false;
      if(isset($response['order'])){
        if(count($response['order']['payments']) > 0){
          if($response['order']['payments'][0]['state'] === "Authorized"){
            $paiementFait = true;
            
            $montant = round(floatval($response['order']['payments'][0]['amount']) / 100, 2);
            
            // Enregistrer le paiement
            $SQLrequete = "select TabCommande.*
, TabReglement.id_cmdrglt
from \${prefixe}fse_commande as TabCommande
left join \${prefixe}fse_cmdrglt as TabReglement on TabReglement.id_cmd = TabCommande.id_cmd and TabReglement.CbCheckoutId = :CbCheckoutId
where TabCommande.id_cmd = :id_cmd";
            $SQLparams = Array(':id_cmd' => $id_cmd,
                    ':CbCheckoutId' => $CbCheckOutId);
            $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

            $commande = $tabResult[0];

            if($tabResult[0]['id_cmdrglt'] === null){
              
              $SQLrequete = "insert into \${prefixe}fse_cmdrglt (id_cmd, type_cmdrglt, montant_cmdrglt, date_cmdrglt, CbCheckoutId)
values(:id_cmd, 4, :montant_cmdrglt, :date_cmdrglt, :CbCheckoutId)";
              $SQLparams = Array(
                ':id_cmd' => $commande['id_cmd'],
                ':montant_cmdrglt' => $montant,
                ':date_cmdrglt' => date("Y-m-d H:i:s"),
                ':CbCheckoutId' => $CbCheckOutId
              );
              $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

              $SQLrequete = "update \${prefixe}fse_commande set dtvalid_cmd = :dtvalid_cmd where id_cmd = :id_cmd";
              $SQLparams = Array(':id_cmd' => $commande['id_cmd'], ':dtvalid_cmd' => date("Y-m-d H:i:s"));
              $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

              // récupérer le modèle HTML pour le mail
              $SQLrequete = "select modele_mhl
from \${prefixe}modelehtml
where code_mhl = 'MAIL_VALID_CMD_FSE'";
              $SQLparams = Array();
              $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

              // si il n'existe pas
              if(count($tabResult) != 1)
              {
                $globalData['errCode'] = "ERR_GEN_02";
                $globalData['errLib'] = "Le modèle HTML du mail de validation est introuvable";
                throw new Exception('ERREUR_PDF');
              }

              // réaliser la fusion HTML
              $dataFusion = array_merge($commande, $pConfig);
              $fusion = new FusionJsonHtml($dataFusion, $tabResult[0]['modele_mhl']);
              $htmlBody = $fusion->process();

              // envoyer le mail
              envoyerMail($commande['mail_cmd'], "FSE - CoopSco - Validation commande", $htmlBody);
            }
          }
        }
      }
      
      if($paiementFait === false){
        $globalData['message']['response']['code'] = "URL";
        if($CbCheckoutDateHeureDepasse === 1){
          $params = $apiOauth2->getParametre();
          $endpoint = "/organizations/".$params['asso-slug']."/checkout-intents";
          $dataApi =[
              'totalAmount' => $montant100,
              'initialAmount' => $montant100,
              'itemName' => 'Commande N°'.$num_cmd.' CoopSco Auguste Mailloux',
              'backUrl' => $pConfig['site_url']."/index.html?action=paiementcb&contexte=abandon",
              'errorUrl' => $pConfig['site_url']."/index.html?action=paiementcb&contexte=ko",
              'returnUrl' => $pConfig['site_url']."/index.html?action=paiementcb&contexte=ok",
              'containsDonation' => false,
              'payer' => [
                  'firstName' => $firstName,
                  'lastName' => $lastName,
                  'email' => $email
              ],
              'metadata' => Array(
                  'id_cmd' => $id_cmd
              )
          ];
          $response = $apiOauth2->callApi("POST", $endpoint, $dataApi);

          $checkoutId = $response['id'];
          $checkoutUrl = $response['redirectUrl'];

          // enregistrer 
          $SQLrequete = "update \${prefixe}fse_commande
    set CbCheckoutId = :CbCheckoutId
    , CbCheckoutDateHeure = DATE_ADD(NOW(), INTERVAL 14 minute)
    where id_cmd = :id_cmd";
          $SQLparams = Array(':id_cmd' => $id_cmd,
              ':CbCheckoutId' => $checkoutId);
          $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
          $globalData['message']['response']['url'] = $checkoutUrl;
          
        }else{
          $globalData['message']['response']['url'] = $checkoutUrl;
        }
        
        
      }else{
        $globalData['message']['response']['code'] = "PAIEMENT";
      }
      
      
    }
    
    

  }

  
  //Ajouter historique action
  if($globalData['context'] !== 'lier'){
    ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}fse_commande", 'id_cmd', $globalData['params']['fse_commande']['id_cmd']);
  }
}