<?php

global $tabWebServices;
$tabWebServices['getComponent'] = [false]; // [accredité]

function ws_getComponent($request)
{
  // récupération des variables globale
  global $pConfig;
  global $globalData;
  
  // Récupérer les paramétrage de l'application coté client
  $tabResult = $globalData['db']->prepLancerExcep( "select paramClient_par from \${prefixe}parametre",
          Array(),
          $globalData['errCode'],
          $globalData['errLib']);

  // désérialiser le résultat
  $moduleParam = (array) json_decode($tabResult[0]['paramClient_par'], true);
  
  // Transmettre les paramètres de l'application
  $globalData['message']['response']['params'] = $moduleParam;
  //$globalData['message']['response']['params'] = $pConfig['params'];
  
  // préparation de l'objet de menu
  $globalData['message']['response']['menu'] = [];
  
  // Récupérer données du composant
  $SQLrequete = "SELECT * from \${prefixe}composant where cd_com = :cd_com";
  $SQLparams = Array(':cd_com' => $globalData['params']['cd_com']);
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

  // si il n'existe pas
  if(count($tabResult) == 0)
  {
    $globalData['errCode'] = "ERR_COM_01";
    $globalData['errLib'] = "Nouveau composant non paramétré [".$globalData['params']['cd_com']."]";

    $SQLrequete = "INSERT INTO \${prefixe}composant(cd_com)
values(:cd_com)";
    $SQLparams = Array(':cd_com' => $globalData['params']['cd_com']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    throw new Exception('ERREUR_COMMIT');
  }
  
  // sauvegarder les données du composant
  $dataComposant = $tabResult[0];

  // l'utilisateur est-il autorisé à avoir accés à ce composant
  $SQLrequete = "select 1
from \${prefixe}autorisation, \${prefixe}groupe
where \${prefixe}autorisation.type_acc = 'COM'
and \${prefixe}autorisation.code_acc = :cd_com
and :token_tok = ''
and \${prefixe}autorisation.id_gro = \${prefixe}groupe.id_gro
and \${prefixe}groupe.cd_gro = 'NC'
union
select 1
from \${prefixe}autorisation, \${prefixe}token, \${prefixe}gro_uti
where \${prefixe}autorisation.type_acc = 'COM'
and \${prefixe}autorisation.code_acc = :cd_com
and \${prefixe}token.token_tok = :token_tok
and \${prefixe}gro_uti.id_uti = \${prefixe}token.id_uti
and \${prefixe}autorisation.id_gro = \${prefixe}gro_uti.id_gro";
  $SQLparams = Array(':token_tok' => $globalData['token'], ':cd_com' => $globalData['params']['cd_com']);
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  
    // si il n'existe pas
  if(count($tabResult) == 0)
  {
    $globalData['errCode'] = "ERR_AUT_01";
    $globalData['errLib'] = "Vous n'êtes pas autorisé à utiliser ce composant [". $globalData['params']['cd_com'] ."]";
    throw new Exception('ERREUR_SEC');
  }
  
  // récupérer le menu du composant
  $SQLrequete = "select \${prefixe}menu.*
from \${prefixe}menu, \${prefixe}autorisation, \${prefixe}groupe
where \${prefixe}menu.cd_com = :cd_com
and \${prefixe}menu.typeOption_mco = 'COM'
and \${prefixe}autorisation.type_acc = 'COM'
and \${prefixe}autorisation.code_acc = \${prefixe}menu.cdComDest_mco
and \${prefixe}groupe.id_gro = \${prefixe}autorisation.id_gro
and :token_tok = ''
and \${prefixe}groupe.cd_gro = 'NC'
union
select \${prefixe}menu.*
from \${prefixe}menu, \${prefixe}autorisation, \${prefixe}token, \${prefixe}gro_uti
where \${prefixe}menu.cd_com = :cd_com
and \${prefixe}menu.typeOption_mco = 'COM'
and \${prefixe}autorisation.type_acc = 'COM'
and \${prefixe}autorisation.code_acc = \${prefixe}menu.cdComDest_mco
and \${prefixe}token.token_tok = :token_tok
and \${prefixe}gro_uti.id_uti = \${prefixe}token.id_uti
and \${prefixe}autorisation.id_gro = \${prefixe}gro_uti.id_gro
union
select \${prefixe}menu.*
from \${prefixe}menu
where \${prefixe}menu.cd_com = :cd_com
and \${prefixe}menu.typeOption_mco in ('FON', 'SEP')
order by position_mco";
  $SQLparams = Array(':token_tok' => $globalData['token'], ':cd_com' => $globalData['params']['cd_com']);
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  
  // Construire le menu attendu par la partie cliente
  $idxMenuSave = -1.00;
  $sousMenu = false;
  $idxTabMenu = -1;
  foreach( $tabResult as $idMenu => $menu )
  {
    // si changement d'index principal
    if(floor($menu['position_mco']) != floor($idxMenuSave)){
      $globalData['message']['response']['menu'][] = [$menu['nom_mco'], $menu['glyphicon_mco'], $menu['typeOption_mco'], $menu['cdComDest_mco']];
      $sousMenu = false;
      $idxTabMenu ++;
      $idxMenuSave = floor($menu['position_mco']);
    }
    else
    {
      if(!$sousMenu){
        $globalData['message']['response']['menu'][$idxTabMenu][3] = [];
      }
      $globalData['message']['response']['menu'][$idxTabMenu][3][] = [$menu['nom_mco'], $menu['glyphicon_mco'], $menu['typeOption_mco'], $menu['cdComDest_mco']];
    }
  }
  
  $globalData['message']['response']['combo'] = [];
  
  // récupérer la liste des requêtes des combos
  $SQLrequete = "select *
from \${prefixe}requete
where code_req = :code_req";
  $SQLparams = Array(':code_req' => $globalData['params']['cd_com'] .':combo');
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

  $tabCombo = $tabResult;
  foreach( $tabCombo as $idReq => $requete )
  {
    $SQLrequete = $requete["select_req"];
    $SQLparams = Array();
    foreach($globalData['params'] as $key => $value){
      if(strpos($SQLrequete, ':'.$key) !== false){
        $SQLparams[':'.$key] = $value;
      }
    }
    // vérifier si utilisation du token dans la requête
    if(strpos($SQLrequete, ':token') !== false){
      $SQLparams[':token'] = $globalData['token'];
    }
    
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $globalData['message']['response']['combo'][$requete["result_req"]] = $tabResult;
  }
  
  
}