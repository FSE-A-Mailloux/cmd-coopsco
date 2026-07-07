<?php
require_once('./vendor/autoload.php');
require_once('./phpClasses/class.FusionJsonHtml.php');
use Spipu\Html2Pdf\Html2Pdf;

global $tabWebServices;
$tabWebServices['etatManage'] = [false]; // [accredité]

function ws_etatManage($request)
{
  // récupération des variables globale
  global $globalData;
  global $pConfig;
 
  if($globalData['context'] == 'print'){
    
    // l'utilisateur est-il autorisé à avoir accés à cette état
    $SQLrequete = "select 1
  from \${prefixe}autorisation, \${prefixe}token, \${prefixe}gro_uti
  where \${prefixe}autorisation.type_acc = 'ETAT'
  and \${prefixe}autorisation.code_acc = :cd_epm
  and \${prefixe}token.token_tok = :token_tok
  and \${prefixe}gro_uti.id_uti = \${prefixe}token.id_uti
  and \${prefixe}autorisation.id_gro = \${prefixe}gro_uti.id_gro";
    $SQLparams = Array(':token_tok' => $globalData['token'], ':cd_epm' => $globalData['params']['etat']['cd_epm']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // si il n'existe pas
    if(count($tabResult) == 0)
    {
      $globalData['errCode'] = "ERR_AUT_01";
      $globalData['errLib'] = "Vous n'êtes pas autorisé à utiliser cet etat [". $globalData['params']['etat']['cd_epm'] ."]";
      throw new Exception('ERREUR_SEC');
    }

    // récupérer le paramétrage de l'état
    $SQLrequete = "select *
from \${prefixe}etatparam
where cd_epm = :cd_epm";
    $SQLparams = Array(':cd_epm' => $globalData['params']['etat']['cd_epm']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // si il n'existe pas
    if(count($tabResult) == 0)
    {
      $globalData['errCode'] = "ERR_ETAT_01";
      $globalData['errLib'] = "Etat non paramétré : [". $globalData['params']['cd_com'] ."]";
      throw new Exception('ERREUR');
    }
    
    $etatparam = $tabResult[0];
    
    if(intval($etatparam['typedata_epm']) === 1){

      // Récupérer les requêtes de l'état
      $SQLrequete = "select *
  from \${prefixe}requete
  where code_req = :code_req";
      $SQLparams = Array(':code_req' => $etatparam['code_req']);
      $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

      // exécuter les requêtes de l'état
      $json = Array();
      foreach( $tabResult as $idReq => $rowReq )
      {

        $SQLrequete = $rowReq['select_req'];
        $nomResponse = $rowReq['result_req'];

        // Créer le tableau des bind
        $SQLparams = Array();
        foreach($globalData['params'] as $key => $value){
          if(strpos($SQLrequete, ':'.$key) !== false){
            $SQLparams[':'.$key] = $value;
          }
        }
        // vérifier si utilisation du token dans la requête
        if(strpos($SQLrequete, ':token') !== false){
          $SQLparams[':token'] = $globalData['token'];
        }

        // exécuter la requête
        $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

        $json[$nomResponse] = $tabResult;
      }
    }elseif(intval($etatparam['typedata_epm']) === 2) {

     $SQLrequete = "select fonction_epf
from \${prefixe}etatfonction
where code_epf = :code_epf";
      $SQLparams = Array(':code_epf' =>$etatparam['code_epf']);
      $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

      $fonctionWs = $tabResult[0]['fonction_epf'];
      $json['data'] = $fonctionWs($request);
    }
    
    // récupérer le modèle pdf de l'état
    $SQLrequete = "select modele_mhl
from \${prefixe}modelehtml
where code_mhl = :code_mhl";
    $SQLparams = Array(':code_mhl' =>$etatparam['code_mhl']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // réaliser la fusion HTML
    $json = array_merge($json, $pConfig);
    $fusion = new FusionJsonHtml($json, $tabResult[0]['modele_mhl']);
    $result = $fusion->process();

    // Générer le PDF
    $nomPdf = $etatparam['code_mhl']. ".pdf";
    $html2pdf = new Html2Pdf();
    $html2pdf->writeHTML($result);
    $pdfString = $html2pdf->output($nomPdf,'S');

    $globalData['message']['response']['pdfBase64'] = base64_encode($pdfString);
    $globalData['message']['response']['nomPdf'] = $nomPdf;

    //Ajouter historique action
    ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}etatparam", 'cd_epm', $globalData['params']['etat']['cd_epm']);

  }
}

function data_fse_commandeCb6(){
  // récupération des variables globale
  global $globalData;
  global $pConfig;
  
  $resultat = Array();

  // requête listant toutes les commandes réglées par CB
  $SQLrequete = "SELECT TabCommande.*, dtbutoir_ann, lib_ann
FROM \${prefixe}fse_annee as TabAnnee
INNER JOIN \${prefixe}fse_commande AS TabCommande on TabCommande.id_ann = TabAnnee.id_ann
INNER JOIN (
	SELECT id_cmd, max(classe_cmf) as classemax
    FROM \${prefixe}fse_cmdenf as TabCmdEnf
    GROUP BY id_cmd
) AS TabCmdEnfClasseMax ON TabCmdEnfClasseMax.id_cmd = TabCommande.id_cmd and TabCmdEnfClasseMax.classemax = 6
WHERE TabAnnee.encours_ann = 1
AND EXISTS (SELECT 1
    FROM \${prefixe}fse_cmdrglt AS TabRglt 
    WHERE TabRglt.id_cmd = TabCommande.id_cmd
    AND TabRglt.type_cmdrglt = 4
    AND dateannul_cmdrglt is null)";
  $SQLparams = Array();
  $tabCommandes = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

  foreach($tabCommandes as $idxCmd => $commande){
    array_push($resultat, $commande);
  
    
    $timestamp = strtotime($commande['dtbutoir_ann']);
    $resultat[$idxCmd]['dtbutoir_ann'] = str_replace(' 01 ', ' 1er ', strftime('%A %d %B %Y', $timestamp));
    $timestamp = strtotime($commande['dtcre_cmd']);
    $resultat[$idxCmd]['dtcre_cmd'] = strftime('%d/%m/%Y', $timestamp);

    // liste des enfants
    $SQLrequete = "SELECT nom_cmf as nom, prenom_cmf as prenom, classe_cmf as classe
FROM \${prefixe}fse_cmdenf
WHERE id_cmd = :id_cmd";
    $SQLparams = Array(':id_cmd' => $commande['id_cmd']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $resultat[$idxCmd]['descenf_cmd'] = $tabResult;

    // liste des articles
    $SQLrequete = "SELECT \${prefixe}fse_article.*, nbr_acd, prix_acd
FROM \${prefixe}fse_artcmd, \${prefixe}fse_article
WHERE \${prefixe}fse_artcmd.id_cmd = :id_cmd
and \${prefixe}fse_article.id_art = \${prefixe}fse_artcmd.id_art
order by ordre_art";
    $SQLparams = Array(':id_cmd' => $commande['id_cmd']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // créer la liste des articles
    $articles = array();
    $total = 0.0;
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
    
    $resultat[$idxCmd]['articles_pdf'] = $articles;
    $resultat[$idxCmd]['total'] = number_format((float)$total, 2, '.', ''). ' €';
    
    
    
  }
  
  return $resultat;
}

function data_fse_commandeCb5(){
  // récupération des variables globale
  global $globalData;
  global $pConfig;
  
  $resultat = Array();

  // requête listant toutes les commandes réglées par CB
  $SQLrequete = "SELECT TabCommande.*, dtbutoir_ann, lib_ann
FROM \${prefixe}fse_annee as TabAnnee
INNER JOIN \${prefixe}fse_commande AS TabCommande on TabCommande.id_ann = TabAnnee.id_ann
INNER JOIN (
	SELECT id_cmd, max(classe_cmf) as classemax
    FROM \${prefixe}fse_cmdenf as TabCmdEnf
    GROUP BY id_cmd
) AS TabCmdEnfClasseMax ON TabCmdEnfClasseMax.id_cmd = TabCommande.id_cmd and TabCmdEnfClasseMax.classemax = 5
WHERE TabAnnee.encours_ann = 1
AND EXISTS (SELECT 1
    FROM \${prefixe}fse_cmdrglt AS TabRglt 
    WHERE TabRglt.id_cmd = TabCommande.id_cmd
    AND TabRglt.type_cmdrglt = 4
    AND dateannul_cmdrglt is null)";
  $SQLparams = Array();
  $tabCommandes = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

  foreach($tabCommandes as $idxCmd => $commande){
    array_push($resultat, $commande);
  
    
    $timestamp = strtotime($commande['dtbutoir_ann']);
    $resultat[$idxCmd]['dtbutoir_ann'] = str_replace(' 01 ', ' 1er ', strftime('%A %d %B %Y', $timestamp));
    $timestamp = strtotime($commande['dtcre_cmd']);
    $resultat[$idxCmd]['dtcre_cmd'] = strftime('%d/%m/%Y', $timestamp);

    // liste des enfants
    $SQLrequete = "SELECT nom_cmf as nom, prenom_cmf as prenom, classe_cmf as classe
FROM \${prefixe}fse_cmdenf
WHERE id_cmd = :id_cmd";
    $SQLparams = Array(':id_cmd' => $commande['id_cmd']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $resultat[$idxCmd]['descenf_cmd'] = $tabResult;

    // liste des articles
    $SQLrequete = "SELECT \${prefixe}fse_article.*, nbr_acd, prix_acd
FROM \${prefixe}fse_artcmd, \${prefixe}fse_article
WHERE \${prefixe}fse_artcmd.id_cmd = :id_cmd
and \${prefixe}fse_article.id_art = \${prefixe}fse_artcmd.id_art
order by ordre_art";
    $SQLparams = Array(':id_cmd' => $commande['id_cmd']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // créer la liste des articles
    $articles = array();
    $total = 0.0;
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
    
    $resultat[$idxCmd]['articles_pdf'] = $articles;
    $resultat[$idxCmd]['total'] = number_format((float)$total, 2, '.', ''). ' €';
    
    
    
  }
  
  return $resultat;
}

function data_fse_commandeCb4(){
  // récupération des variables globale
  global $globalData;
  global $pConfig;
  
  $resultat = Array();

  // requête listant toutes les commandes réglées par CB
  $SQLrequete = "SELECT TabCommande.*, dtbutoir_ann, lib_ann
FROM \${prefixe}fse_annee as TabAnnee
INNER JOIN \${prefixe}fse_commande AS TabCommande on TabCommande.id_ann = TabAnnee.id_ann
INNER JOIN (
	SELECT id_cmd, max(classe_cmf) as classemax
    FROM \${prefixe}fse_cmdenf as TabCmdEnf
    GROUP BY id_cmd
) AS TabCmdEnfClasseMax ON TabCmdEnfClasseMax.id_cmd = TabCommande.id_cmd and TabCmdEnfClasseMax.classemax = 4
WHERE TabAnnee.encours_ann = 1
AND EXISTS (SELECT 1
    FROM \${prefixe}fse_cmdrglt AS TabRglt 
    WHERE TabRglt.id_cmd = TabCommande.id_cmd
    AND TabRglt.type_cmdrglt = 4
    AND dateannul_cmdrglt is null)";
  $SQLparams = Array();
  $tabCommandes = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

  foreach($tabCommandes as $idxCmd => $commande){
    array_push($resultat, $commande);
  
    
    $timestamp = strtotime($commande['dtbutoir_ann']);
    $resultat[$idxCmd]['dtbutoir_ann'] = str_replace(' 01 ', ' 1er ', strftime('%A %d %B %Y', $timestamp));
    $timestamp = strtotime($commande['dtcre_cmd']);
    $resultat[$idxCmd]['dtcre_cmd'] = strftime('%d/%m/%Y', $timestamp);

    // liste des enfants
    $SQLrequete = "SELECT nom_cmf as nom, prenom_cmf as prenom, classe_cmf as classe
FROM \${prefixe}fse_cmdenf
WHERE id_cmd = :id_cmd";
    $SQLparams = Array(':id_cmd' => $commande['id_cmd']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $resultat[$idxCmd]['descenf_cmd'] = $tabResult;

    // liste des articles
    $SQLrequete = "SELECT \${prefixe}fse_article.*, nbr_acd, prix_acd
FROM \${prefixe}fse_artcmd, \${prefixe}fse_article
WHERE \${prefixe}fse_artcmd.id_cmd = :id_cmd
and \${prefixe}fse_article.id_art = \${prefixe}fse_artcmd.id_art
order by ordre_art";
    $SQLparams = Array(':id_cmd' => $commande['id_cmd']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // créer la liste des articles
    $articles = array();
    $total = 0.0;
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
    
    $resultat[$idxCmd]['articles_pdf'] = $articles;
    $resultat[$idxCmd]['total'] = number_format((float)$total, 2, '.', ''). ' €';
    
    
    
  }
  
  return $resultat;
}


function data_fse_commandeCb3(){
  // récupération des variables globale
  global $globalData;
  global $pConfig;
  
  $resultat = Array();

  // requête listant toutes les commandes réglées par CB
  $SQLrequete = "SELECT TabCommande.*, dtbutoir_ann, lib_ann
FROM \${prefixe}fse_annee as TabAnnee
INNER JOIN \${prefixe}fse_commande AS TabCommande on TabCommande.id_ann = TabAnnee.id_ann
INNER JOIN (
	SELECT id_cmd, max(classe_cmf) as classemax
    FROM \${prefixe}fse_cmdenf as TabCmdEnf
    GROUP BY id_cmd
) AS TabCmdEnfClasseMax ON TabCmdEnfClasseMax.id_cmd = TabCommande.id_cmd and TabCmdEnfClasseMax.classemax = 3
WHERE TabAnnee.encours_ann = 1
AND EXISTS (SELECT 1
    FROM \${prefixe}fse_cmdrglt AS TabRglt 
    WHERE TabRglt.id_cmd = TabCommande.id_cmd
    AND TabRglt.type_cmdrglt = 4
    AND dateannul_cmdrglt is null)";
  $SQLparams = Array();
  $tabCommandes = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

  foreach($tabCommandes as $idxCmd => $commande){
    array_push($resultat, $commande);
  
    
    $timestamp = strtotime($commande['dtbutoir_ann']);
    $resultat[$idxCmd]['dtbutoir_ann'] = str_replace(' 01 ', ' 1er ', strftime('%A %d %B %Y', $timestamp));
    $timestamp = strtotime($commande['dtcre_cmd']);
    $resultat[$idxCmd]['dtcre_cmd'] = strftime('%d/%m/%Y', $timestamp);

    // liste des enfants
    $SQLrequete = "SELECT nom_cmf as nom, prenom_cmf as prenom, classe_cmf as classe
FROM \${prefixe}fse_cmdenf
WHERE id_cmd = :id_cmd";
    $SQLparams = Array(':id_cmd' => $commande['id_cmd']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $resultat[$idxCmd]['descenf_cmd'] = $tabResult;

    // liste des articles
    $SQLrequete = "SELECT \${prefixe}fse_article.*, nbr_acd, prix_acd
FROM \${prefixe}fse_artcmd, \${prefixe}fse_article
WHERE \${prefixe}fse_artcmd.id_cmd = :id_cmd
and \${prefixe}fse_article.id_art = \${prefixe}fse_artcmd.id_art
order by ordre_art";
    $SQLparams = Array(':id_cmd' => $commande['id_cmd']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // créer la liste des articles
    $articles = array();
    $total = 0.0;
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
    
    $resultat[$idxCmd]['articles_pdf'] = $articles;
    $resultat[$idxCmd]['total'] = number_format((float)$total, 2, '.', ''). ' €';
    
    
    
  }
  
  return $resultat;
}