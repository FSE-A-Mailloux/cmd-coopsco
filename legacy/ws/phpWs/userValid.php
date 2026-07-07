<?php

global $tabWebServices;
$tabWebServices['userValid'] = [false]; // [accredité]

function ws_userValid($request)
{
  // récupération des variables globale
  global $db;
  global $message;
  global $ErrCode;
  global $ErrLib;
  global $pConfig;
  
  $params = $request['params'];
  $token = $request['token'];
  
  //vérifier la correspondance entre code activation et id_uti
  $id_uti = $params['id_uti'];
  $codeValidation_uti = $params['codeValidation_uti'];
  
  // Récupérer les informations utilisateur
  $SQLrequete = "SELECT utilisateur.*, groupe.cd_com
from utilisateur
left join gro_uti on gro_uti.id_uti = utilisateur.id_uti and gro_uti.princ_gro_uti = 1
left join groupe on groupe.id_gro = gro_uti.id_gro
where utilisateur.id_uti = :id_uti
and codeValidation_uti = :codeValidation_uti";
  $SQLparams = Array(':id_uti' => $id_uti, ':codeValidation_uti' => $codeValidation_uti);
  $tabResult = $db->prepLancerExcep($SQLrequete, $SQLparams, $ErrCode, $ErrLib);

  // si il n'existe pas
  if(count($tabResult) != 1)
  {
    $ErrCode = "ERR_GEN_02";
    $ErrLib = "Le code saisi est incorrect";
    throw new Exception('ERREUR_FCN');
  }
  
  $dateValidation = date("Y-m-d H:i:s");
  $message['response']['cd_com'] = $tabResult[0]['cd_com'];
  $message['response']['compte'] = $tabResult[0]['compte_uti'];
  $message['response']['id_uti'] = $tabResult[0]['id_uti'];
  $message['response']['dateValidation_uti'] = $dateValidation;
  
  // Faire un un update de l'utilisateur avec la date de validation
  $SQLrequete = "update utilisateur
set dateValidation_uti = :dateValidation_uti
where id_uti = :id_uti";
  $SQLparams = Array(':id_uti' => $id_uti, ':dateValidation_uti' => $dateValidation);
  $tabResult = $db->prepLancerExcep($SQLrequete, $SQLparams, $ErrCode, $ErrLib); 
  
  // générer un token
  $token = md5(uniqid(rand(), true));
  $dateTok = date("Y-m-d H:i:s");
  
  $SQLrequete = "delete from token
where id_uti = :id_uti";
  $SQLparams = Array(':id_uti' => $id_uti);
  $tabResult = $db->prepLancerExcep($SQLrequete, $SQLparams, $ErrCode, $ErrLib);
  
  $SQLrequete = "insert into token(id_uti, token_tok, date_tok)
values(:id_uti, :token_tok, :date_tok)";
  $SQLparams = Array(':id_uti' => $id_uti, ':token_tok' => $token, ':date_tok' => $dateTok);
  $tabResult = $db->prepLancerExcep($SQLrequete, $SQLparams, $ErrCode, $ErrLib);

  $message['response']['token'] = $token;
  
}