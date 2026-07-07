<?php

global $tabWebServices;
$tabWebServices['functionManage'] = [true]; // [accredité]

function ws_functionManage($request)
{
  // récupération des variables globale
  global $globalData;
  
  // selon le contexte
  if($globalData['context'] == 'add'){
    $SQLrequete = "insert into \${prefixe}fonction(cd_fon, lib_fon)
select :cd_fon, :lib_fon
from dual
where not exists(select 1 from \${prefixe}fonction ta where ta.cd_fon = :cd_fon)";
    $SQLparams = Array(
        ':cd_fon' => $globalData['params']['fonction']['cd_fon'],
        ':lib_fon' => $globalData['params']['fonction']['lib_fon']
    );
    
  }else if($globalData['context'] == 'mod'){
    $SQLrequete = "update \${prefixe}fonction
set lib_fon = :lib_fon
where cd_fon = :cd_fon";
    $SQLparams = Array(
        ':cd_fon' => $globalData['params']['fonction']['cd_fon'],
        ':lib_fon' => $globalData['params']['fonction']['lib_fon']
    );
    
  }else if($globalData['context'] == 'del'){
    // suppresion autorisation
    $SQLrequete = "delete
from \${prefixe}autorisation
where type_acc = 'FON'
and code_acc = :cd_fon";
    $SQLparams = Array(':cd_fon' => $globalData['params']['fonction']['cd_fon']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $SQLrequete = "delete
from \${prefixe}fonction
where cd_fon = :cd_fon";
    $SQLparams = Array(':cd_fon' => $globalData['params']['fonction']['cd_fon']);
  }
  
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  
  //Ajouter historique action
  ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}fonction", 'cd_fon', $globalData['params']['fonction']['cd_fon']);

}