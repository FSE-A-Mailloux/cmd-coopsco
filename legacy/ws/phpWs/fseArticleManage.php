<?php

global $tabWebServices;
$tabWebServices['fseArticleManage'] = [true]; // [accredité]

function ws_fseArticleManage($request)
{
  // récupération des variables globale
  global $globalData;
  
  // selon le contexte
  if($globalData['context'] == 'add'){
    $SQLrequete = "insert into \${prefixe}fse_article(lib_art, marque_art, type_art, code_art, ordre_art, id_fou, cmdfam_art, stock_art, conso_art)
values(:lib_art, :marque_art, :type_art, :code_art, :ordre_art, :id_fou, :cmdfam_art, :stock_art, :conso_art)";
    $SQLparams = Array(
        ':lib_art' => $globalData['params']['fse_article']['lib_art'],
        ':marque_art' => $globalData['params']['fse_article']['marque_art'],
        ':type_art' => $globalData['params']['fse_article']['type_art'],
        ':code_art' => $globalData['params']['fse_article']['code_art'],
        ':id_fou' => $globalData['params']['fse_article']['id_fou'],
        ':ordre_art' => $globalData['params']['fse_article']['ordre_art'],
        ':cmdfam_art' => ($globalData['params']['fse_article']['cmdfam_art'] ? 1:0),
        ':stock_art' => ($globalData['params']['fse_article']['stock_art'] ? 1:0),
        ':conso_art' => ($globalData['params']['fse_article']['conso_art'] ? 1:0)
    );
    
  }else if($globalData['context'] == 'mod'){
    $SQLrequete = "update \${prefixe}fse_article
set lib_art = :lib_art,
  marque_art = :marque_art,
  type_art = :type_art,
  code_art = :code_art,
  ordre_art = :ordre_art,
  id_fou = :id_fou,
  cmdfam_art = :cmdfam_art,
  stock_art = :stock_art,
  conso_art = :conso_art
where id_art = :id_art";
    $SQLparams = Array(
        ':lib_art' => $globalData['params']['fse_article']['lib_art'],
        ':marque_art' => $globalData['params']['fse_article']['marque_art'],
        ':type_art' => $globalData['params']['fse_article']['type_art'],
        ':code_art' => $globalData['params']['fse_article']['code_art'],
        ':ordre_art' => $globalData['params']['fse_article']['ordre_art'],
        ':id_art' => $globalData['params']['fse_article']['id_art'],
        ':cmdfam_art' => ($globalData['params']['fse_article']['cmdfam_art'] ? 1:0),
        ':stock_art' => ($globalData['params']['fse_article']['stock_art'] ? 1:0),
        ':conso_art' => ($globalData['params']['fse_article']['conso_art'] ? 1:0),
        ':id_fou' => $globalData['params']['fse_article']['id_fou']
    );
    
  }else if($globalData['context'] == 'del'){
    $SQLrequete = "delete
from \${prefixe}fse_stock
where id_art = :id_art";
    $SQLparams = Array(':id_art' => $globalData['params']['fse_article']['id_art']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $SQLrequete = "delete
from \${prefixe}fse_prixanneefamille
where id_art = :id_art";
    $SQLparams = Array(':id_art' => $globalData['params']['fse_article']['id_art']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $SQLrequete = "delete
from \${prefixe}fse_prixanneefournisseur
where id_art = :id_art";
    $SQLparams = Array(':id_art' => $globalData['params']['fse_article']['id_art']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
   
    $SQLrequete = "delete
from \${prefixe}fse_article
where id_art = :id_art";
    $SQLparams = Array(':id_art' => $globalData['params']['fse_article']['id_art']);
    
  }
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  
  //Ajouter historique action
  ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}fse_article", 'id_art', $globalData['params']['fse_article']['id_art']);

}