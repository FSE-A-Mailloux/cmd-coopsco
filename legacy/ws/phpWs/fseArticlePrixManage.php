<?php

global $tabWebServices;
$tabWebServices['fseArticlePrixManage'] = [true]; // [accredité]

function ws_fseArticlePrixManage($request)
{
  // récupération des variables globale
  global $globalData;
  
  // selon le contexte
  if($globalData['context'] == 'copie'){
    // vérifier qu'il existe une année en cours
    $SQLrequete = "select id_ann from \${prefixe}fse_annee where encours_ann = 1";
    $SQLparams = Array();
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    if(count($tabResult) == 0)
    {
      $globalData['errCode'] = "ERR_ANN_01";
      $globalData['errLib'] = "Aucune année en cours";
      throw new Exception('ERREUR');
    }
    
    $id_ann = $tabResult[0]['id_ann'];
    
    $SQLrequete = "delete from \${prefixe}fse_prixanneefamille
where id_ann = :id_ann";
    $SQLparams = Array(
        ':id_ann' => $globalData['params']['fse_annee']['id_ann']
    );
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $SQLrequete = "delete from \${prefixe}fse_prixanneefournisseur
where id_ann = :id_ann";
    $SQLparams = Array(
        ':id_ann' => $globalData['params']['fse_annee']['id_ann']
    );
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $SQLrequete = "insert into \${prefixe}fse_prixanneefamille(id_ann, id_art, prix_pafe)
select :id_ann_dest, id_art, prix_pafe from \${prefixe}fse_prixanneefamille where id_ann = :id_ann_encours";
    $SQLparams = Array(
        ':id_ann_dest' => $globalData['params']['fse_annee']['id_ann'],
        ':id_ann_encours' => $id_ann
    );
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $SQLrequete = "insert into \${prefixe}fse_prixanneefournisseur(id_ann, id_art, lot_pafr, prix_pafr)
select :id_ann_dest, id_art, lot_pafr, prix_pafr from \${prefixe}fse_prixanneefournisseur where id_ann = :id_ann_encours";
    $SQLparams = Array(
        ':id_ann_dest' => $globalData['params']['fse_annee']['id_ann'],
        ':id_ann_encours' => $id_ann
    );
    
    
  }else if($globalData['context'] == 'mod'){
    // boucler sur les périodes
    foreach( $globalData['params']['fse_articleprix']['periodes'] as $idx => $periode ){
      if($periode['verrou_ann'] != 1){
        $SQLrequete = "update \${prefixe}fse_prixanneefamille
set prix_pafe = :prix_pafe
where id_ann = :id_ann
and id_art = :id_art";
        $SQLparams = Array(
            ':prix_pafe' => $periode['prix_pafe'],
            ':id_ann' => $periode['id_ann'],
            ':id_art' => $globalData['params']['fse_articleprix']['id_art']
        );
        $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
        
        $SQLrequete = "update \${prefixe}fse_prixanneefournisseur
set prix_pafr = :prix_pafr,
lot_pafr = :lot_pafr
where id_ann = :id_ann
and id_art = :id_art";
        $SQLparams = Array(
            ':prix_pafr' => $periode['prix_pafr'],
            ':lot_pafr' => $periode['lot_pafr'],
            ':id_ann' => $periode['id_ann'],
            ':id_art' => $globalData['params']['fse_articleprix']['id_art']
        );
        $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
        
        $SQLrequete = "insert into \${prefixe}fse_prixanneefamille(id_ann, id_art, prix_pafe)
select :id_ann, :id_art, :prix_pafe
from dual
where not exists (select 1 from \${prefixe}fse_prixanneefamille ta where ta.id_art = :id_art and ta.id_ann = :id_ann)";
        $SQLparams = Array(
            ':prix_pafe' => $periode['prix_pafe'],
            ':id_ann' => $periode['id_ann'],
            ':id_art' => $globalData['params']['fse_articleprix']['id_art']
        );
        $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
        
        $SQLrequete = "insert into \${prefixe}fse_prixanneefournisseur(id_ann, id_art, prix_pafr, lot_pafr)
select :id_ann, :id_art, :prix_pafr, :lot_pafr
from dual
where not exists (select 1 from \${prefixe}fse_prixanneefournisseur ta where ta.id_art = :id_art and ta.id_ann = :id_ann)";
        $SQLparams = Array(
            ':prix_pafr' => $periode['prix_pafr'],
            ':lot_pafr' => $periode['lot_pafr'],
            ':id_ann' => $periode['id_ann'],
            ':id_art' => $globalData['params']['fse_articleprix']['id_art']
        );
        $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
      }
    }
    return;
  }
  
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  
  //Ajouter historique action
  if($globalData['context'] == 'copie'){
    ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}fse_prixanneefamille", 'id_ann', $globalData['params']['fse_annee']['id_ann']);
  }else{
    ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}fse_articleprix", 'id_art', $globalData['params']['fse_articleprix']['id_art']);
  }
  
}