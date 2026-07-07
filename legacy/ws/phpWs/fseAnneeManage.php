<?php

global $tabWebServices;
$tabWebServices['fseAnneeManage'] = [true]; // [accredité]

function ws_fseAnneeManage($request)
{
  // récupération des variables globale
  global $globalData;
  
  // selon le contexte
  if($globalData['context'] == 'add'){
    $SQLrequete = "insert into \${prefixe}fse_annee(lib_ann, encours_ann, verrou_ann, dtbutoir_ann, prefix_ann)
values(:lib_ann, :encours_ann, :verrou_ann, :dtbutoir_ann, :prefix_ann)";
    $SQLparams = Array(
        ':lib_ann' => $globalData['params']['fse_annee']['lib_ann'],
        ':dtbutoir_ann' => $globalData['params']['fse_annee']['dtbutoir_ann'],
        ':prefix_ann' => $globalData['params']['fse_annee']['prefix_ann'],
        ':encours_ann' => $globalData['params']['fse_annee']['encours_ann'],
        ':verrou_ann' => ($globalData['params']['fse_annee']['verrou_ann'] ? 1:0)
    );
  }else if($globalData['context'] == 'mod'){
    $SQLrequete = "update \${prefixe}fse_annee
set lib_ann = :lib_ann,
  dtbutoir_ann = :dtbutoir_ann,
  prefix_ann = :prefix_ann,
  encours_ann = :encours_ann,
  verrou_ann = :verrou_ann
where id_ann = :id_ann";
    $SQLparams = Array(
        ':lib_ann' => $globalData['params']['fse_annee']['lib_ann'],
        ':dtbutoir_ann' => $globalData['params']['fse_annee']['dtbutoir_ann'],
        ':prefix_ann' => $globalData['params']['fse_annee']['prefix_ann'],
        ':encours_ann' => $globalData['params']['fse_annee']['encours_ann'],
        ':verrou_ann' => ($globalData['params']['fse_annee']['verrou_ann'] ? 1:0),
        ':id_ann' => $globalData['params']['fse_annee']['id_ann']
    );
    
  }else if($globalData['context'] == 'del'){
    
    
    $SQLrequete = "delete
from \${prefixe}fse_prixanneefamille
where id_ann = :id_ann";
    $SQLparams = Array(':id_ann' => $globalData['params']['fse_annee']['id_ann']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $SQLrequete = "delete
from \${prefixe}fse_prixanneefournisseur
where id_ann = :id_ann";
    $SQLparams = Array(':id_ann' => $globalData['params']['fse_annee']['id_ann']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $SQLrequete = "delete
from \${prefixe}fse_annee
where id_ann = :id_ann";
    $SQLparams = Array(':id_ann' => $globalData['params']['fse_annee']['id_ann']);
    
  }else if($globalData['context'] == 'encours'){
    $SQLrequete = "update \${prefixe}fse_annee
set encours_ann = (
case id_ann
when :id_ann then 1
else 0
end)";
    $SQLparams = Array(':id_ann' => $globalData['params']['fse_annee']['id_ann']);
    
  }else if($globalData['context'] == 'ouvferm'){
    $SQLrequete = "update \${prefixe}fse_annee set ouverte_ann = 
case ouverte_ann
when 1 then 0
else 1
end
where id_ann = :id_ann";
    $SQLparams = Array(':id_ann' => $globalData['params']['fse_annee']['id_ann']);
  }
  
  
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  
  //Ajouter historique action
  ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}fse_annee", 'id_ann', $globalData['params']['fse_annee']['id_ann']);

}