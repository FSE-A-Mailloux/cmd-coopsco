<?php

global $tabWebServices;
$tabWebServices['login'] = [false]; // [accredité]

function ws_login($request)
{
  // récupération des variables globale
  global $globalData;
  
  //$globalData['params'] = $request['params'];
  //$globalData['token'] = $request['token'];
  //echo var_dump($globalData['params']);

  if($globalData['context'] == 'validUser' || $globalData['context'] == 'validLink')
  {
    if($globalData['context'] == 'validUser')
    {
      //vérifier la correspondance entre code activation et id_uti
      $compte_uti = $globalData['params']['compte_uti'];
      $codeValidation_uti = $globalData['params']['codeValidation_uti'];

      // Récupérer les informations utilisateur
      $SQLrequete = "SELECT \${prefixe}utilisateur.*, \${prefixe}groupe.cd_com, \${prefixe}groupe.nom_gro, \${prefixe}gro_uti.princ_gro_uti
from \${prefixe}utilisateur
left join \${prefixe}gro_uti on \${prefixe}gro_uti.id_uti = \${prefixe}utilisateur.id_uti
left join \${prefixe}groupe on \${prefixe}groupe.id_gro = \${prefixe}gro_uti.id_gro
where \${prefixe}utilisateur.compte_uti = :compte_uti
and codeValidation_uti = :codeValidation_uti";
      $SQLparams = Array(':compte_uti' => $compte_uti, ':codeValidation_uti' => $codeValidation_uti);
      $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

      // si il n'existe pas
      if(count($tabResult) != 1)
      {
        $globalData['errCode'] = "ERR_GEN_02";
        $globalData['errLib'] = "Le code saisi est incorrect";
        throw new Exception('ERREUR_FCN');
      }
    }
    else
    {

      $code = $globalData['params']['id'];
     // Récupérer les informations utilisateur
      $SQLrequete = "SELECT \${prefixe}utilisateur.*, \${prefixe}groupe.cd_com, \${prefixe}groupe.nom_gro, \${prefixe}gro_uti.princ_gro_uti
from \${prefixe}utilisateur
left join \${prefixe}gro_uti on \${prefixe}gro_uti.id_uti = \${prefixe}utilisateur.id_uti
left join \${prefixe}groupe on \${prefixe}groupe.id_gro = \${prefixe}gro_uti.id_gro
where concat(md5(\${prefixe}utilisateur.compte_uti), md5(\${prefixe}utilisateur.mail_uti), \${prefixe}utilisateur.codeValidation_uti) = :code";
      $SQLparams = Array(':code' => $code);
      $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

      // si il n'existe pas
      if(count($tabResult) != 1)
      {
        $globalData['errCode'] = "ERR_GEN_02";
        $globalData['errLib'] = "Le code saisi est incorrect";
        throw new Exception('ERREUR_FCN');
      }
    }
    
    $id_uti = $tabResult[0]['id_uti'];
    $compte_uti = $tabResult[0]['compte_uti'];
    $globalData['message']['response']['tabGroupe'] = Array();
    
    // Parcourir les réponses
    foreach( $tabResult as $idxComp => $compUser )
    {
      if($compUser['princ_gro_uti'] == 1){
        $com = $compUser['cd_com'];
      }
      array_push($globalData['message']['response']['tabGroupe'], Array('nom_gro'=>$compUser['nom_gro'], 'cd_com'=>$compUser['cd_com']));
    }
    
    
    $dateValidation = date("Y-m-d H:i:s");
    
    // Faire un un update de l'utilisateur avec la date de validation
    $SQLrequete = "update \${prefixe}utilisateur
set dateValidation_uti = :dateValidation_uti
where id_uti = :id_uti";
    $SQLparams = Array(':id_uti' => $id_uti, ':dateValidation_uti' => $dateValidation);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
  }
  else
  {
    $compte_uti = $globalData['params']['compte_uti'];
    
    // Récupérer données de l'utilisateur
    $SQLrequete = "SELECT \${prefixe}utilisateur.*, \${prefixe}groupe.cd_com, \${prefixe}groupe.nom_gro, \${prefixe}gro_uti.princ_gro_uti
from \${prefixe}utilisateur
left join \${prefixe}gro_uti on \${prefixe}gro_uti.id_uti = \${prefixe}utilisateur.id_uti
left join \${prefixe}groupe on \${prefixe}groupe.id_gro = \${prefixe}gro_uti.id_gro
where compte_uti = :compte_uti
and pass_uti = :pass_uti";
    $SQLparams = Array(':compte_uti' => $globalData['params']['compte_uti'], ':pass_uti' => hash('sha256',$globalData['params']['pass_uti']));
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // si il n'existe pas
    if(count($tabResult) == 0)
    {
      $globalData['errCode'] = "ERR_LOG_01";
      $globalData['errLib'] = "Login ou mot de passe incorrect";
      throw new Exception('ERREUR_FCN');
    }

    $id_uti = $tabResult[0]['id_uti'];
    $dateValidation = $tabResult[0]['dateValidation_uti'];
    $globalData['message']['response']['tabGroupe'] = Array();
    
    // Parcourir les réponses
    foreach( $tabResult as $idxComp => $compUser )
    {
      if($compUser['princ_gro_uti'] == 1){
        $com = $compUser['cd_com'];
      }
      array_push($globalData['message']['response']['tabGroupe'], Array('nom_gro'=>$compUser['nom_gro'], 'cd_com'=>$compUser['cd_com']));
    }
  }
  
  if($com =='')
  {
    $globalData['errCode'] = "ERR_LOG_02";
    $globalData['errLib'] = "Ce compte n'est pas rattaché à un groupe";
    throw new Exception('ERREUR_FCN');
  }
  
  $globalData['message']['response']['cd_com'] = $com;
  $globalData['message']['response']['compte_uti'] = $compte_uti;
  $globalData['message']['response']['id_uti'] = $id_uti;
  $globalData['message']['response']['dateValidation_uti'] = $dateValidation;
  
  
  // générer un token
  $globalData['token'] = md5(uniqid(rand(), true));
  $dateTok = date("Y-m-d H:i:s");
  
  $SQLrequete = "insert into \${prefixe}token(id_uti, token_tok, date_tok)
values(:id_uti, :token_tok, :date_tok)";
  $SQLparams = Array(':id_uti' => $id_uti, ':token_tok' => $globalData['token'], ':date_tok' => $dateTok);
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

  $globalData['message']['response']['token'] = $globalData['token'];
}

?>
