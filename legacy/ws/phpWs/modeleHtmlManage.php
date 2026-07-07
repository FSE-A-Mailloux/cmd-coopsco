<?php
require_once('./vendor/autoload.php');
require_once('./phpClasses/class.FusionJsonHtml.php');
use Spipu\Html2Pdf\Html2Pdf;
      
global $tabWebServices;
$tabWebServices['modeleHtmlManage'] = [true]; // [accredité]

function ws_modeleHtmlManage($request)
{
  
  // récupération des variables globale
  global $globalData;
  global $pConfig;
  
  // selon le contexte
  if($globalData['context'] == 'add'){
    $SQLrequete = "insert into \${prefixe}modelehtml(code_mhl, lib_mhl, type_mhl, verrou_mhl, modele_mhl)
values(:code_mhl, :lib_mhl, :type_mhl, :verrou_mhl, :modele_mhl)";
    $SQLparams = Array(
        ':code_mhl' => $globalData['params']['modeleHtml']['code_mhl'],
        ':lib_mhl' => $globalData['params']['modeleHtml']['lib_mhl'],
        ':type_mhl' => $globalData['params']['modeleHtml']['type_mhl'],
        ':verrou_mhl' => ($globalData['params']['modeleHtml']['verrou_mhl'] ? 1:0),
        ':modele_mhl' => $globalData['params']['modeleHtml']['modele_mhl']
    );
    
  }else if($globalData['context'] == 'mod'){
    $SQLrequete = "update \${prefixe}modelehtml
set lib_mhl = :lib_mhl,
  type_mhl = :type_mhl,
  verrou_mhl = :verrou_mhl,
  modele_mhl = :modele_mhl
where code_mhl = :code_mhl";
    $SQLparams = Array(
        ':code_mhl' => $globalData['params']['modeleHtml']['code_mhl'],
        ':lib_mhl' => $globalData['params']['modeleHtml']['lib_mhl'],
        ':type_mhl' => $globalData['params']['modeleHtml']['type_mhl'],
        ':verrou_mhl' => ($globalData['params']['modeleHtml']['verrou_mhl'] ? 1:0),
        ':modele_mhl' => $globalData['params']['modeleHtml']['modele_mhl']
    );
    
  }else if($globalData['context'] == 'del'){
    // Supression de la l
    
    
    $SQLrequete = "delete
from \${prefixe}modelehtml
where code_mhl = :code_mhl";
    $SQLparams = Array(':code_mhl' => $globalData['params']['modeleHtml']['code_mhl']);
    
  }else if($globalData['context'] == 'test'){
    // Réaliser la fusion
    $dataFusion = array_merge($globalData['params']['modeleHtml']['jsonTest'], $pConfig);
    $fusion = new FusionJsonHtml($dataFusion, $globalData['params']['modeleHtml']['modele_mhl']);
    $result = $fusion->process();

    // si envoi de mail
    if($globalData['params']['modeleHtml']['type_mhl'] == 'MAIL'){
      envoyerMail($globalData['utilisateur']['mail_uti'], $pConfig['site_nom'] . " : mail de test", $result);
    }else{
      $html2pdf = new Html2Pdf();
      $html2pdf->writeHTML($result);
      $pdfString = $html2pdf->output('test.pdf','S');
      //echo $pdfString;
      $globalData['message']['response']['pdfBase64'] = base64_encode($pdfString);
    }
      
  }
  
  
  if($globalData['context'] != 'test'){
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    //Ajouter historique action
    ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}modelehtml", 'code_mhl', $globalData['params']['modeleHtml']['code_mhl']);

  }
}