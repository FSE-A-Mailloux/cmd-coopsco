<?php
require_once('./vendor/autoload.php');
require_once('./phpClasses/class.FusionJsonHtml.php');
use Spipu\Html2Pdf\Html2Pdf;

global $tabWebServices;
$tabWebServices['fseOutilsManage'] = [true]; // [accredité]

function ws_fseOutilsManage($request)
{
  // récupération des variables globale
  global $globalData;
  global $pConfig;
 
  if($globalData['context'] == 'getTabCmdCb'){
    
    // requête listant toutes les commandes réglées par CB
    $SQLrequete = "SELECT TabCommande.id_cmd
FROM \${prefixe}fse_annee as TabAnnee
INNER JOIN \${prefixe}fse_commande AS TabCommande on TabCommande.id_ann = TabAnnee.id_ann
WHERE TabAnnee.encours_ann = 1
AND EXISTS (SELECT 1
    FROM \${prefixe}fse_cmdrglt AS TabRglt 
    WHERE TabRglt.id_cmd = TabCommande.id_cmd
    AND TabRglt.type_cmdrglt = 4
    AND dateannul_cmdrglt is null)
ORDER BY TabCommande.id_cmd";
    $SQLparams = Array();
    $tabCommandes = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    $tabGroupeId = Array();
    $idmin = 0;
    $idcourant = 0;
    $compteur = 0;
    
    foreach( $tabCommandes as $idx => $commande ){
      $compteur ++;
      $idcourant = intval($commande['id_cmd']);
      if($idmin === 0){
        $idmin = $idcourant;
      }
      if($compteur % 30 === 0){
        array_push($tabGroupeId, Array('idmin' => $idmin, 'idmax' => $idcourant));
        $idmin = 0;
      }
    }
    
    if($idmin !== 0){
      array_push($tabGroupeId, Array('idmin' => $idmin, 'idmax' => $idcourant));
    }
    
    $globalData['message']['response']['TabCmdCb'] = $tabGroupeId;
    
  }elseif($globalData['context'] == 'genPdfCmdCb'){
    $id_cmd_min = intval($globalData['params']['id_cmd_min']);
    $id_cmd_max = intval($globalData['params']['id_cmd_max']);
    
    // récupérer le modèle pdf de l'état
    $SQLrequete = "select modele_mhl
from \${prefixe}modelehtml
where code_mhl = :code_mhl";
    $SQLparams = Array(':code_mhl' => 'PDF_FSE_CMDS');
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // si il n'existe pas
    if(count($tabResult) == 0)
    {
      $globalData['errCode'] = "ERR_OUT_01";
      $globalData['errLib'] = "Le modèle PDF_FSE_CMDS n'existe pas";
      throw new Exception('ERREUR');
    }
    
    $modele_mhl = $tabResult[0]['modele_mhl'];
    
    // requête listant toutes les commandes réglées par CB
    $SQLrequete = "SELECT TabCommande.*, dtbutoir_ann, lib_ann
FROM \${prefixe}fse_annee as TabAnnee
INNER JOIN \${prefixe}fse_commande AS TabCommande on TabCommande.id_ann = TabAnnee.id_ann
WHERE TabAnnee.encours_ann = 1
AND EXISTS (SELECT 1
    FROM \${prefixe}fse_cmdrglt AS TabRglt 
    WHERE TabRglt.id_cmd = TabCommande.id_cmd
    AND TabRglt.type_cmdrglt = 4
    AND dateannul_cmdrglt is null)
AND TabCommande.id_cmd between :id_cmd_min and :id_cmd_max
ORDER BY TabCommande.id_cmd";
    $SQLparams = Array(':id_cmd_min' => $id_cmd_min, ':id_cmd_max' => $id_cmd_max);
    $tabCommandes = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $resultat = Array();
    
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
    $json = Array("data" => $resultat);
    $json = array_merge($json, $pConfig);
    
    $fusion = new FusionJsonHtml($json, $modele_mhl);
    $result = $fusion->process();

    // Générer le PDF
    $nomPdf = $modele_mhl. ".pdf";
    $html2pdf = new Html2Pdf();
    $html2pdf->writeHTML($result);
    $pdfString = $html2pdf->output($nomPdf,'S');

    $globalData['message']['response']['pdfBase64'] = base64_encode($pdfString);
    $globalData['message']['response']['nomPdf'] = $nomPdf;
    
  }
  
}
    