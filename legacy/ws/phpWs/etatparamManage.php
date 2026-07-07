<?php
require_once('./etatManage.php');

require_once('./vendor/autoload.php');
require_once('./phpClasses/class.FusionJsonHtml.php');
use Spipu\Html2Pdf\Html2Pdf;

global $tabWebServices;
$tabWebServices['etatparamManage'] = [true]; // [accredité]

function ws_etatparamManage($request)
{
  // récupération des variables globale
  global $globalData;
  global $pConfig;

  // selon le contexte
  if($globalData['context'] == 'add'){
    $SQLrequete = "insert into \${prefixe}etatparam(cd_epm, lib_epm, typedata_epm, code_mhl, code_req, code_epf)
values(:cd_epm, :lib_epm, :typedata_epm, :code_mhl, :code_req, :code_epf)";
    $SQLparams = Array(
        ':cd_epm' => $globalData['params']['etatparam']['cd_epm'],
        ':lib_epm' => $globalData['params']['etatparam']['lib_epm'],
        ':typedata_epm' => $globalData['params']['etatparam']['typedata_epm'],
        ':code_mhl' => $globalData['params']['etatparam']['code_mhl'],
        ':code_req' => $globalData['params']['etatparam']['code_req'],
        ':code_epf' => $globalData['params']['etatparam']['code_epf']
    );
    
  }else if($globalData['context'] == 'mod'){
    $SQLrequete = "update \${prefixe}etatparam
set code_mhl = :code_mhl,
  lib_epm = :lib_epm,
  typedata_epm = :typedata_epm,
  code_req = :code_req,
  code_epf = :code_epf
where cd_epm = :cd_epm";
    $SQLparams = Array(
        ':code_mhl' => $globalData['params']['etatparam']['code_mhl'],
        ':lib_epm' => $globalData['params']['etatparam']['lib_epm'],
        ':typedata_epm' => $globalData['params']['etatparam']['typedata_epm'],
        ':code_req' => $globalData['params']['etatparam']['code_req'],
        ':code_epf' => $globalData['params']['etatparam']['code_epf'],
        ':cd_epm' => $globalData['params']['etatparam']['cd_epm']
    );
    
  }else if($globalData['context'] == 'del'){
    $SQLrequete = "delete
from \${prefixe}etatparam
where cd_epm = :cd_epm";
    $SQLparams = Array(':cd_epm' => $globalData['params']['etatparam']['cd_epm']);
  
  }else if($globalData['context'] == 'test'){
    
    // récupérer le modèle html de l'état
    $SQLrequete = "select modele_mhl
from \${prefixe}modelehtml
where code_mhl = :code_mhl";
    $SQLparams = Array(':code_mhl' =>$globalData['params']['etatparam']['code_mhl']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    $modeleHtml = $tabResult[0]['modele_mhl'];
    
    
    if(intval($globalData['params']['etatparam']['typedata_epm']) === 1){

      // Récupérer les requêtes
      $SQLrequete = "SELECT select_req, result_req
    FROM \${prefixe}requete
    WHERE code_req = :code_req";
      $SQLparams = Array(':code_req' => $globalData['params']['etatparam']['code_req']);
      $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

      $requestData = Array();

      foreach( $tabResult as $idReq => $rowReq )
      {

        $SQLrequete = $rowReq['select_req'];
        $nomResponse = $rowReq['result_req'];

        // Créer le tableau des bind
        $SQLparams = Array();
        foreach($globalData['params']['etatparam']['jsonTest'] as $key => $value){
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

        $requestData[$nomResponse] = $tabResult;
      }
      
    }elseif (intval($globalData['params']['etatparam']['typedata_epm']) === 2) {
      
     $SQLrequete = "select fonction_epf
from \${prefixe}etatfonction
where code_epf = :code_epf";
      $SQLparams = Array(':code_mhl' =>$globalData['params']['etatparam']['code_epf']);
      $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

      $fonctionWs = $tabResult[0]['fonction_epf'];
      $requestData['data'] = $fonctionWs($request);
    }
    
    // réaliser la fusion HTML
    $requestData = array_merge($requestData, $pConfig);
    $fusion = new FusionJsonHtml($requestData, $modeleHtml);
    $result = $fusion->process();
    
    // Générer le PDF
    $nomPdf = "test.pdf";
    $html2pdf = new Html2Pdf();
    $html2pdf->writeHTML($result);
    $pdfString = $html2pdf->output($nomPdf,'S');

    $globalData['message']['response']['pdfBase64'] = base64_encode($pdfString);
    $globalData['message']['response']['nomPdf'] = $nomPdf;
    
  }
  if($globalData['context'] !== 'test'){
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    //Ajouter historique action
    ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}etatparam", 'cd_epm', $globalData['params']['etatparam']['cd_epm']);

  }
}