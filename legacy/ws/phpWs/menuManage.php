<?php

global $tabWebServices;
$tabWebServices['menuManage'] = [true]; // [accredité]

function ws_menuManage($request)
{
  // récupération des variables globale
  global $globalData;
  
  // selon le contexte
  if($globalData['context'] == 'add'){

    if($globalData['params']['menu']['typeOption_mco'] == 'SEP'){
      $globalData['params']['menu']['cdComDest_mco'] = '';
    }
    
    $SQLrequete = "insert into \${prefixe}menu(cd_com, position_mco, nom_mco, glyphicon_mco, typeOption_mco, cdComDest_mco)
values(:cd_com, :position_mco, :nom_mco, :glyphicon_mco, :typeOption_mco, :cdComDest_mco)";
    $SQLparams = Array(
        ':cd_com' => $globalData['params']['menu']['cd_com'],
        ':position_mco' => $globalData['params']['menu']['position_mco'],
        ':nom_mco' => $globalData['params']['menu']['nom_mco'],
        ':glyphicon_mco' => $globalData['params']['menu']['glyphicon_mco'],
        ':typeOption_mco' => $globalData['params']['menu']['typeOption_mco'],
        ':cdComDest_mco' => $globalData['params']['menu']['cdComDest_mco']
    );
    
  }else if($globalData['context'] == 'mod'){

    if($globalData['params']['menu']['typeOption_mco'] == 'SEP'){
      $globalData['params']['menu']['cdComDest_mco'] = '';
    }
    
    $SQLrequete = "update \${prefixe}menu
set cd_com = :cd_com,
  position_mco = :position_mco,
  nom_mco = :nom_mco,
  glyphicon_mco = :glyphicon_mco,
  typeOption_mco = :typeOption_mco,
  cdComDest_mco = :cdComDest_mco
where id_mco = :id_mco";
    $SQLparams = Array(
        ':id_mco' => $globalData['params']['menu']['id_mco'],
        ':cd_com' => $globalData['params']['menu']['cd_com'],
        ':position_mco' => $globalData['params']['menu']['position_mco'],
        ':nom_mco' => $globalData['params']['menu']['nom_mco'],
        ':glyphicon_mco' => $globalData['params']['menu']['glyphicon_mco'],
        ':typeOption_mco' => $globalData['params']['menu']['typeOption_mco'],
        ':cdComDest_mco' => $globalData['params']['menu']['cdComDest_mco']
    );
    
  }else if($globalData['context'] == 'del'){
    $SQLrequete = "delete
from \${prefixe}menu
where id_mco = :id_mco";
    $SQLparams = Array(':id_mco' => $globalData['params']['menu']['id_mco']);
    
  }
  
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  
  //Ajouter historique action
  ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}menu", 'cd_com', $globalData['params']['menu']['cd_com']);

}