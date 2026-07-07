<?php

global $tabWebServices;
$tabWebServices['fseFournisseurManage'] = [true]; // [accredité]

function ws_fseFournisseurManage($request)
{
  // récupération des variables globale
  global $globalData;
  
  // selon le contexte
  if($globalData['context'] == 'add'){
    $SQLrequete = "insert into \${prefixe}fse_fournisseur(design_fou, adr1_fou, adr2_fou, adr3_fou, cp_fou, ville_fou, tel_fou, mail_fou)
values(:design_fou, :adr1_fou, :adr2_fou, :adr3_fou, :cp_fou, :ville_fou, :tel_fou, :mail_fou)";
    $SQLparams = Array(
        ':design_fou' => $globalData['params']['fse_fournisseur']['design_fou'],
        ':adr1_fou' => $globalData['params']['fse_fournisseur']['adr1_fou'],
        ':adr2_fou' => $globalData['params']['fse_fournisseur']['adr2_fou'],
        ':adr3_fou' => $globalData['params']['fse_fournisseur']['adr3_fou'],
        ':cp_fou' => $globalData['params']['fse_fournisseur']['cp_fou'],
        ':ville_fou' => $globalData['params']['fse_fournisseur']['ville_fou'],
        ':tel_fou' => $globalData['params']['fse_fournisseur']['tel_fou'],
        ':mail_fou' => $globalData['params']['fse_fournisseur']['mail_fou']
    );
    
  }else if($globalData['context'] == 'mod'){
    $SQLrequete = "update \${prefixe}fse_fournisseur
set design_fou = :design_fou,
  adr1_fou = :adr1_fou,
  adr2_fou = :adr2_fou,
  adr3_fou = :adr3_fou,
  cp_fou = :cp_fou,
  ville_fou = :ville_fou,
  tel_fou = :tel_fou,
  mail_fou = :mail_fou
where id_fou = :id_fou";
    $SQLparams = Array(
        ':design_fou' => $globalData['params']['fse_fournisseur']['design_fou'],
        ':adr1_fou' => $globalData['params']['fse_fournisseur']['adr1_fou'],
        ':adr2_fou' => $globalData['params']['fse_fournisseur']['adr2_fou'],
        ':adr3_fou' => $globalData['params']['fse_fournisseur']['adr3_fou'],
        ':cp_fou' => $globalData['params']['fse_fournisseur']['cp_fou'],
        ':ville_fou' => $globalData['params']['fse_fournisseur']['ville_fou'],
        ':tel_fou' => $globalData['params']['fse_fournisseur']['tel_fou'],
        ':mail_fou' => $globalData['params']['fse_fournisseur']['mail_fou'],
        ':id_fou' => $globalData['params']['fse_fournisseur']['id_fou']
    );
    
  }else if($globalData['context'] == 'del'){
    $SQLrequete = "update \${prefixe}fse_article set id_fou = null
where id_fou = :id_fou";
    $SQLparams = Array(':id_fou' => $globalData['params']['fse_fournisseur']['id_fou']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    $SQLrequete = "delete
from \${prefixe}fse_fournisseur
where id_fou = :id_fou";
    $SQLparams = Array(':id_fou' => $globalData['params']['fse_fournisseur']['id_fou']);
    
  }
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

  //Ajouter historique action
  ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}fse_fournisseur", 'id_fou', $globalData['params']['fse_fournisseur']['id_fou']);

}