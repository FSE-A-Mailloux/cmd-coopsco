<?php
require_once('./vendor/autoload.php');
require_once('./phpClasses/class.FusionJsonHtml.php');
use Spipu\Html2Pdf\Html2Pdf;

global $tabWebServices;
$tabWebServices['fseConsolidationManage'] = [true]; // [accredité]

function ws_fseConsolidationManage($request)
{
  // récupération des variables globale
  global $globalData;
  global $pConfig;
  
  // selon le contexte
  if($globalData['context'] == 'new'){
    
    // récupérer le modèle HTML pour la fusion
    $SQLrequete = "select modele_mhl
from \${prefixe}modelehtml
where code_mhl = 'PDF_CONSOLIDATION_FSE'";
    $SQLparams = Array();
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // si il n'existe pas
    if(count($tabResult) != 1)
    {
      $globalData['errCode'] = "ERR_GEN_02";
      $globalData['errLib'] = "Le modèle HTML du PDF de consolidation est introuvable";
      throw new Exception('ERREUR_PDF');
    }
    
    // réaliser la fusion HTML
    $dataFusion = array_merge($globalData['params']['fse_consolidation'], $pConfig);
    $fusion = new FusionJsonHtml($dataFusion, $tabResult[0]['modele_mhl']);
    $result = $fusion->process();
    
    // Générer le PDF
    $nomPdf = "consolidation_coopSco_" .date('Ymj'). ".pdf";
    $html2pdf = new Html2Pdf();
    $html2pdf->writeHTML($result);
    $pdfString = $html2pdf->output($nomPdf,'S');

    $globalData['message']['response']['pdfBase64'] = base64_encode($pdfString);
    $globalData['message']['response']['nomPdf'] = $nomPdf;
    
    //Ajouter historique action
    ajouter_actionhisto($globalData['action'], $globalData['context'], "", '', '');

  }
}