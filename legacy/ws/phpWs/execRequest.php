<?php

global $tabWebServices;
$tabWebServices['execRequest'] = [true]; // [accredité]

function ws_execRequest($request)
{
  // récupération des variables globale
  global $globalData;
  
  // Récupérer la requête
  $SQLrequete = "SELECT select_req, result_req
FROM \${prefixe}requete
WHERE code_req = :code_req";
  $SQLparams = Array(':code_req' => $globalData['context']);
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  
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

    $globalData['message']['response'][$nomResponse] = $tabResult;
  }
}