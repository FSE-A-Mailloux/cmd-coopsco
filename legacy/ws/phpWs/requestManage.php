<?php

global $tabWebServices;
$tabWebServices['requestManage'] = [true]; // [accredité]

function ws_requestManage($request)
{
  // récupération des variables globale
  global $globalData;
  
  // selon le contexte
  if($globalData['context'] == 'add'){
    $SQLrequete = "insert into \${prefixe}requete(code_req, lib_req, result_req, select_req)
values(:code_req, :lib_req, :result_req, :select_req)";
    $SQLparams = Array(
        ':code_req' => $globalData['params']['request']['code_req'],
        ':lib_req' => $globalData['params']['request']['lib_req'],
        ':result_req' => $globalData['params']['request']['result_req'],
        ':select_req' => $globalData['params']['request']['select_req']
    );
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $SQLrequete = "insert into \${prefixe}fonction(cd_fon, lib_fon)
select :cd_fon, :lib_fon
from dual
where not exists(select 1 from \${prefixe}fonction ta where ta.cd_fon = :cd_fon)";
    $SQLparams = Array(
        ':cd_fon' => 'execRequest:'.$globalData['params']['request']['code_req'],
        ':lib_fon' => $globalData['params']['request']['lib_req']
    );
    
  }else if($globalData['context'] == 'mod'){
    $SQLrequete = "update \${prefixe}requete
set code_req = :code_req,
  lib_req = :lib_req,
  result_req = :result_req,
  select_req = :select_req
where id_req = :id_req";
    $SQLparams = Array(
        ':id_req' => $globalData['params']['request']['id_req'],
        ':code_req' => $globalData['params']['request']['code_req'],
        ':lib_req' => $globalData['params']['request']['lib_req'],
        ':result_req' => $globalData['params']['request']['result_req'],
        ':select_req' => $globalData['params']['request']['select_req']
    );
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  
    $SQLrequete = "update \${prefixe}fonction
set lib_fon = :lib_fon
where cd_fon = :cd_fon";
    $SQLparams = Array(
        ':cd_fon' => 'execRequest:'.$globalData['params']['request']['code_req'],
        ':lib_fon' => $globalData['params']['request']['lib_req']
    );
    
    
  }else if($globalData['context'] == 'del'){
    $SQLrequete = "delete
from \${prefixe}requete
where id_req = :id_req";
    $SQLparams = Array(':id_req' => $globalData['params']['request']['id_req']);
    
    // TODO : ajouter la suppression de la fonction et des autorisation
    
  }
  
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  
  //Ajouter historique action
  ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}requete", 'id_req', $globalData['params']['request']['id_req']);

}