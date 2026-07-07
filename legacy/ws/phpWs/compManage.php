<?php

global $tabWebServices;
$tabWebServices['compManage'] = [true]; // [accredité]

function ws_compManage($request)
{
  // récupération des variables globale
  global $globalData;
  
  // selon le contexte
  if($globalData['context'] == 'add'){
    $SQLrequete = "insert into \${prefixe}composant(cd_com, lib_com, verrou_com)
select :cd_com, :lib_com, :verrou_com
from dual
where not exists(select 1 from \${prefixe}composant ta where ta.cd_com = :cd_com)";
    $SQLparams = Array(
        ':cd_com' => $globalData['params']['com']['cd_com'],
        ':lib_com' => $globalData['params']['com']['lib_com'],
        ':verrou_com' => ($globalData['params']['com']['verrou_com'] ? 1:0)
    );
    
  }else if($globalData['context'] == 'mod'){
    $SQLrequete = "update \${prefixe}composant
set lib_com = :lib_com, verrou_com = :verrou_com
where cd_com = :cd_com";
    $SQLparams = Array(
        ':cd_com' => $globalData['params']['com']['cd_com'],
        ':lib_com' => $globalData['params']['com']['lib_com'],
        ':verrou_com' => ($globalData['params']['com']['verrou_com'] ? 1:0)
    );
    
  }else if($globalData['context'] == 'del'){
    // autorisation
    $SQLrequete = "delete
from \${prefixe}autorisation
where type_acc = 'COM'
and code_acc = :cd_com";
    $SQLparams = Array(':cd_com' => $globalData['params']['com']['cd_com']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // menu
    $SQLrequete = "delete
from \${prefixe}menu
where cd_com = :cd_com";
    $SQLparams = Array(':cd_com' => $globalData['params']['com']['cd_com']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // update groupe
    $SQLrequete = "update \${prefixe}groupe
set cd_com = 'default'
where cd_com = :cd_com";
    $SQLparams = Array(':cd_com' => $globalData['params']['com']['cd_com']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $SQLrequete = "delete
from \${prefixe}composant
where cd_com = :cd_com";
    $SQLparams = Array(':cd_com' => $globalData['params']['com']['cd_com']);
  }
  
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

  //Ajouter historique action
  ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}composant", 'cd_com', $globalData['params']['com']['cd_com']);

}