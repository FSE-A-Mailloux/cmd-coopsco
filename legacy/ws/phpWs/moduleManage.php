<?php

global $tabWebServices;
$tabWebServices['moduleManage'] = [true]; // [accredité]

function ws_moduleManage($request)
{
  // récupération des variables globale
  global $globalData;
  
  // selon le contexte
  if($globalData['context'] == 'params'){
    $SQLrequete = "update \${prefixe}module
set param_mod = :param_mod
where cd_mod = :cd_mod";
    $SQLparams = Array(
        ':param_mod' => $globalData['params']['moduleParams']['param_mod'],
        ':cd_mod' => $globalData['params']['moduleParams']['cd_mod']
    );
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // Récupérer tous les paramètre des modules dans l'ordre
    $SQLrequete = "select position_mod, param_mod from \${prefixe}module order by position_mod";
    $SQLparams = Array(
        ':param_mod' => $globalData['params']['moduleParams']['param_mod'],
        ':cd_mod' => $globalData['params']['moduleParams']['cd_mod']
    );
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // Tableau de paramètres
    $tabParamClient = Array();
    $tabParamServeur = Array();
    
    // Charger la chaine en tableau json
    foreach($tabResult as $idResult => $unParam )
    {
      $paramMod = (array) json_decode($unParam['param_mod'], true);
      foreach( $paramMod['tabGroupeParam'] as $idGroupe => $groupeParam )
      {
        foreach( $groupeParam['tabParam'] as $idParam => $param )
        {
          if($param['cible'] === 'CS' || $param['cible'] === 'C')
          {
            $tabParamClient[$param['code']] = $param['valeur'];
          }
          if($param['cible'] === 'CS' || $param['cible'] === 'S')
          {
            $tabParamServeur[$param['code']] = $param['valeur'];
          }
        }
      }
    }
    
    $SQLrequete = "update \${prefixe}parametre
set paramClient_par = :paramClient_par, paramServeur_par = :paramServeur_par";
    $SQLparams = Array(
        ':paramClient_par' => json_encode($tabParamClient),
        ':paramServeur_par' => json_encode($tabParamServeur)
    );
    
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    //Ajouter historique action
    ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}module", 'cd_mod', $globalData['params']['moduleParams']['cd_mod']);

  }
  

}

?>