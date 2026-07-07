<?php

global $tabWebServices;
$tabWebServices['groupManage'] = [true]; // [accredité]

function ws_groupManage($request)
{
  // récupération des variables globale
  global $globalData;
  
  // Récupérer la valeur minimal de niveau du groupe
  $SQLrequete = "select ifnull(min(\${prefixe}groupe.niveau_gro), 99) as NivMin
from \${prefixe}token
inner join \${prefixe}gro_uti on \${prefixe}gro_uti.id_uti = \${prefixe}token.id_uti
inner join \${prefixe}groupe on \${prefixe}groupe.id_gro = \${prefixe}gro_uti.id_gro
where \${prefixe}token.token_tok = :token";
  $SQLparams = Array(
      ':token' => $globalData['token']
  );
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  
  $niveauMinUtilisateur = $tabResult[0]['NivMin'];
  
  // selon le contexte
  if($globalData['context'] == 'add'){
    
    // Si le niveau transmis est inférieur à celui de l'utilisateur
    if($globalData['params']['group']['niveau_gro'] < $niveauMinUtilisateur)
    {
      $globalData['errCode'] = "ERR_GRO_03";
      $globalData['errLib'] = "Niveau du groupe non autorisé";
      throw new Exception('ERREUR');
    }
    
    $SQLrequete = "insert into \${prefixe}groupe(cd_gro, nom_gro, cd_com, niveau_gro)
values(:cd_gro, :nom_gro, :cd_com, :niveau_gro)";
    $SQLparams = Array(
        ':cd_gro' => $globalData['params']['group']['cd_gro'],
        ':nom_gro' => $globalData['params']['group']['nom_gro'],
        ':cd_com' => $globalData['params']['group']['cd_com'],
        ':niveau_gro' => $globalData['params']['group']['niveau_gro']
    );
    
  }else if($globalData['context'] == 'mod'){
    
    // Si le niveau transmis est inférieur à celui de l'utilisateur
    if($globalData['params']['group']['niveau_gro'] < $niveauMinUtilisateur)
    {
      $globalData['errCode'] = "ERR_GRO_03";
      $globalData['errLib'] = "Niveau du groupe non autorisé";
      throw new Exception('ERREUR');
    }
    
    $SQLrequete = "update \${prefixe}groupe
set cd_gro = :cd_gro,
  nom_gro = :nom_gro,
  cd_com = :cd_com
where id_gro = :id_gro";
    $SQLparams = Array(
        ':cd_gro' => $globalData['params']['group']['cd_gro'],
        ':nom_gro' => $globalData['params']['group']['nom_gro'],
        ':cd_com' => $globalData['params']['group']['cd_com'],
        ':id_gro' => $globalData['params']['group']['id_gro']
    );
    
  }else if($globalData['context'] == 'del'){
    // récupérer le cd_gro depuis l'id
    $SQLrequete = "SELECT cd_gro
from \${prefixe}groupe
where id_gro = :id_gro";
    $SQLparams = Array(':id_gro' => $globalData['params']['group']['id_gro']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // si il n'existe pas
    if(count($tabResult) == 0)
    {
      $globalData['errCode'] = "ERR_GRO_01";
      $globalData['errLib'] = "Groupe non trouvé";
      throw new Exception('ERREUR');
    }else{
      if($tabResult[0]['cd_gro'] == 'ADMIN' || $tabResult[0]['cd_gro'] == 'USER' || $tabResult[0]['cd_gro'] == 'NC'){
        $globalData['errCode'] = "ERR_GRO_02";
        $globalData['errLib'] = "Ce groupe ne peut pas être supprimé";
        throw new Exception('ERREUR');
      }
    }

    $SQLrequete = "delete
from \${prefixe}gro_uti
where id_gro = :id_gro";
    $SQLparams = Array(':id_gro' => $globalData['params']['group']['id_gro']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $SQLrequete = "delete
from \${prefixe}autorisation
where id_gro = :id_gro";
    $SQLparams = Array(':id_gro' => $globalData['params']['group']['id_gro']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $SQLrequete = "delete
from \${prefixe}groupe
where id_gro = :id_gro";
    $SQLparams = Array(':id_gro' => $globalData['params']['group']['id_gro']);
  }
  
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  
  //Ajouter historique action
  ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}groupe", 'id_gro', $globalData['params']['group']['id_gro']);

}