<?php

global $tabWebServices;
$tabWebServices['fseStockManage'] = [true]; // [accredité]

function ws_fseStockManage($request)
{
  // récupération des variables globale
  global $globalData;
  
  // selon le contexte
  if($globalData['context'] == 'mod'){
    $SQLrequete = "update \${prefixe}fse_stock
set nb_sto = :nb_sto
where id_art = :id_art";
    $SQLparams = Array(
        ':nb_sto' => $globalData['params']['fse_stock']['nb_sto'],
        ':id_art' => $globalData['params']['fse_stock']['id_art']
    );
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $SQLrequete = "insert into \${prefixe}fse_stock(id_art, nb_sto)
select :id_art, :nb_sto
from dual
where not exists (select 1 from \${prefixe}fse_stock ta where ta.id_art = :id_art)";
    $SQLparams = Array(
        ':nb_sto' => $globalData['params']['fse_stock']['nb_sto'],
        ':id_art' => $globalData['params']['fse_stock']['id_art']
    );
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    //Ajouter historique action
    ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}fse_article", 'id_art', $globalData['params']['fse_stock']['id_art']);

  }
}