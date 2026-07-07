<?php
require_once('./vendor/autoload.php');
require_once('./phpClasses/class.FusionJsonHtml.php');

global $tabWebServices;
$tabWebServices['userManage'] = [true]; // [accredité]

function ws_userManage($request)
{
  // récupération des variables globale
  global $globalData;
  global $pConfig;
  
  // si contexte de changement sur soit-même
  if($globalData['context'] == 'cha'){
    // l'utilisateur est-il autorisé à avoir accés à ce composant
    $SQLrequete = "SELECT 1
FROM \${prefixe}token
WHERE \${prefixe}token.id_uti = :id_uti
AND \${prefixe}token.token_tok = :token_tok";
    $SQLparams = Array(':token_tok' => $globalData['token'], ':id_uti' => $globalData['params']['user']['id_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // si il n'existe pas
    if(count($tabResult) == 0)
    {
      $globalData['errCode'] = "ERR_AUT_02";
      $globalData['errLib'] = "Vous n'êtes pas autorisé à réaliser cette action [". $request['action'] ."]";
      throw new Exception('ERREUR_SEC');
    }
  }
  
  // si demande de suppression
  if($globalData['context'] == 'del')
  {
    // Vérifier que l'utilisateur en cours n'est pas celui que l'on supprime
    if($globalData['params']['user']['id_uti'] === $globalData['utilisateur']['id_uti']){
      $globalData['errCode'] = "ERR_UTI_02";
      $globalData['errLib'] = "Vous ne pouvez pas vous supprimer.";
      throw new Exception('ERREUR_FCN');
    }
    
    // Récupérer le groupe
    $SQLrequete = "select
(select ifnull(min(\${prefixe}groupe.niveau_gro), 99)
from \${prefixe}gro_uti
inner join \${prefixe}groupe on \${prefixe}groupe.id_gro = \${prefixe}gro_uti.id_gro
where \${prefixe}gro_uti.id_uti = :id_uti) as niveauMinModif,
(select ifnull(min(\${prefixe}groupe.niveau_gro), 99)
from \${prefixe}token
inner join \${prefixe}gro_uti on \${prefixe}gro_uti.id_uti = \${prefixe}token.id_uti
inner join \${prefixe}groupe on \${prefixe}groupe.id_gro = \${prefixe}gro_uti.id_gro
where \${prefixe}token.token_tok = :token_tok) as niveauMinUser
from dual";
    $SQLparams = Array(':token_tok' => $globalData['token'], ':id_uti' => $globalData['params']['user']['id_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // si le niveau de l'utilisateur n'est pas assez élevé
    if($tabResult[0]['niveauMinUser'] > $tabResult[0]['niveauMinModif'])
    {
      $globalData['errCode'] = "ERR_AUT_02";
      $globalData['errLib'] = "Vous ne pouvez pas supprimer un utilisateur ayant des droits plus élevés";
      throw new Exception('ERREUR');
    }
    
    // Anonymiser les dossiers de l'utilisateur
    $SQLrequete = "update \${prefixe}fse_cmdenf
set nom_cmf = 'anonyme'
, prenom_cmf = 'anonyme'
where \${prefixe}fse_cmdenf.id_cmd in (
  select \${prefixe}fse_commande.id_cmd
  from \${prefixe}fse_commande
  where \${prefixe}fse_commande.id_uti = :id_uti
)";
    $SQLparams = Array(':id_uti' => $globalData['params']['user']['id_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
 
    $SQLrequete = "update \${prefixe}fse_commande
set mail_cmd = ''
, tel_cmd = ''
, prenom_cmd = 'anonyme'
, nom_cmd = 'anonyme'
, id_uti = null
where id_uti = :id_uti";
    $SQLparams = Array(':id_uti' => $globalData['params']['user']['id_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
 
    
    $SQLrequete = "delete
from \${prefixe}token
where id_uti = :id_uti";
    $SQLparams = Array(':id_uti' => $globalData['params']['user']['id_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
 
    $SQLrequete = "delete
from \${prefixe}gro_uti
where id_uti = :id_uti";
    $SQLparams = Array(':id_uti' => $globalData['params']['user']['id_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $SQLrequete = "delete
from \${prefixe}actionhisto
where id_uti = :id_uti";
    $SQLparams = Array(':id_uti' => $globalData['params']['user']['id_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $SQLrequete = "delete
from \${prefixe}utilisateur
where id_uti = :id_uti";
    $SQLparams = Array(':id_uti' => $globalData['params']['user']['id_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  }
  // si demmande de modification
  if($globalData['context'] == 'mod' || $globalData['context'] == 'cha')
  {
    // Récupérer le groupe
    $SQLrequete = "select
(select ifnull(min(\${prefixe}groupe.niveau_gro), 99)
from \${prefixe}gro_uti
inner join \${prefixe}groupe on \${prefixe}groupe.id_gro = \${prefixe}gro_uti.id_gro
where \${prefixe}gro_uti.id_uti = :id_uti) as niveauMinModif,
(select ifnull(min(\${prefixe}groupe.niveau_gro), 99)
from \${prefixe}token
inner join \${prefixe}gro_uti on \${prefixe}gro_uti.id_uti = \${prefixe}token.id_uti
inner join \${prefixe}groupe on \${prefixe}groupe.id_gro = \${prefixe}gro_uti.id_gro
where \${prefixe}token.token_tok = :token_tok) as niveauMinUser
from dual";
    $SQLparams = Array(':token_tok' => $globalData['token'], ':id_uti' => $globalData['params']['user']['id_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // si le niveau de l'utilisateur n'est pas assez élevé
    if($tabResult[0]['niveauMinUser'] > $tabResult[0]['niveauMinModif'])
    {
      $globalData['errCode'] = "ERR_AUT_02";
      $globalData['errLib'] = "Vous ne pouvez pas modifier un utilisateur ayant des droits plus élevés";
      throw new Exception('ERREUR');
    }
    
    // Vérifier que le nouveau mail n'est pas attaché à quelqu'un d'autre
    $SQLrequete = "SELECT 1
FROM \${prefixe}utilisateur
WHERE mail_uti = :mail_uti
AND id_uti != :id_uti";
    $SQLparams = Array(':id_uti' => $globalData['params']['user']['id_uti'], ':mail_uti' => $globalData['params']['user']['mail_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // si il existe
    if(count($tabResult) > 0)
    {
      $globalData['errCode'] = "ERR_FCN";
      $globalData['errLib'] = "L'adresse mail est déjà configurée sur un autre utilisateur";
      throw new Exception('ERREUR_FCN');
    }
    
    $SQLrequete = "update \${prefixe}utilisateur
set prenom_uti = :prenom_uti,
 nom_uti = :nom_uti,
 mail_uti = :mail_uti,
comGroupe_uti = :comGroupe_uti
where id_uti = :id_uti";
    
    $SQLparams = Array(':id_uti' => $globalData['params']['user']['id_uti'],
        ':mail_uti' => $globalData['params']['user']['mail_uti'],
        ':nom_uti' => $globalData['params']['user']['nom_uti'],
        ':prenom_uti' => $globalData['params']['user']['prenom_uti'],
        ':comGroupe_uti' => ($globalData['params']['user']['comGroupe_uti'] ? 1 : 0));
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  
  }
  // si changement de mot de passe
  if($globalData['context'] == 'changeMyPass')
  {
    $globalData['params']['user']['id_uti'] = $globalData['utilisateur']['id_uti'];

    // Vérifier l'ancien mot de passe
    $SQLrequete = "SELECT 1
FROM \${prefixe}utilisateur
WHERE \${prefixe}utilisateur.id_uti = :id_uti
AND \${prefixe}utilisateur.pass_uti = :pass_uti";
    $SQLparams = Array(':id_uti' => $globalData['utilisateur']['id_uti'],
            ':pass_uti' => hash('sha256',$globalData['params']['user']['passOld_uti']));
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // si le mot de passe est incorrecte
    if(count($tabResult) == 0)
    {
      $globalData['errCode'] = "ERR_FCN";
      $globalData['errLib'] = "L'ancien mot de passe saisie est incorrect !";
      throw new Exception('ERREUR_FCN');
    }
    
  
    $SQLrequete = "update \${prefixe}utilisateur
set pass_uti = :pass_uti
where id_uti = :id_uti";
    $SQLparams = Array(':id_uti' => $globalData['utilisateur']['id_uti'],
            ':pass_uti' => hash('sha256',$globalData['params']['user']['pass_uti']));
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  

  }
  // si demande d'ajout
  if($globalData['context'] == 'add' || $globalData['context'] == 'new')
  {
    // Vérifier que le compte n'existe pas
    $SQLrequete = "SELECT 1
FROM \${prefixe}utilisateur
WHERE \${prefixe}utilisateur.compte_uti = :compte_uti";
    $SQLparams = Array(':compte_uti' => $globalData['params']['user']['compte_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // si il existe
    if(count($tabResult) > 0)
    {
      $globalData['errCode'] = "ERR_FCN";
      $globalData['errLib'] = "Ce nom de compte existe déjà";
      throw new Exception('ERREUR_FCN');
    }
    
    // Vérifier que le mail n'existe pas
    $SQLrequete = "SELECT 1
FROM \${prefixe}utilisateur
WHERE \${prefixe}utilisateur.mail_uti = :mail_uti";
    $SQLparams = Array(':mail_uti' => $globalData['params']['user']['mail_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // si il existe
    if(count($tabResult) > 0)
    {
      $globalData['errCode'] = "ERR_FCN";
      $globalData['errLib'] = "Cette adresse mail est déjà utilisée par un autre compte";
      throw new Exception('ERREUR_FCN');
    }

    $dateCreation = date("Y-m-d H:i:s");
    $dateValidation = null;
    $codeValidation = "";
    if($globalData['context'] == 'add')
    {
      $dateValidation = $dateCreation;
    }
    else
    {
      $char = 'abcdefghijklmnopqrstuvwxyz0123456789';
      $codeValidation = str_shuffle($char);
      $codeValidation = substr($codeValidation,0, 8);
      
      if($pConfig['captchaPublicKey'] != '')
      {
        // Vérification recaptcha server->server
        $postData =['secret'=>$pConfig['captchaSecretKey'], 'response'=>$globalData['params']['user']['recaptchaResponse']];
        $defaults = array(
          CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_POST => true,
          CURLOPT_POSTFIELDS => $postData,
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_SSL_VERIFYHOST => 2
        );
        $ch = curl_init();
        curl_setopt_array($ch, $defaults);
        $output = curl_exec($ch); 
        curl_close($ch);

        $json = json_decode($output, true);
        if(!$json['success'])
        {
          $globalData['errCode'] = "ERR_CAPTCHA_01";
          $globalData['errLib'] = "clef recaptcha invalidée";
          throw new Exception('ERREUR_SEC');
        }
      }
    }

    $SQLrequete = "insert into \${prefixe}utilisateur(compte_uti, pass_uti, mail_uti, nom_uti, prenom_uti, dateInscription_uti, dateValidation_uti, codeValidation_uti, comGroupe_uti)
select :compte_uti, :pass_uti, :mail_uti, :nom_uti, :prenom_uti, :dateInscription_uti, :dateValidation_uti, :codeValidation_uti, :comGroupe_uti
from DUAL
where not exists(select 1 from \${prefixe}utilisateur ta where ta.compte_uti = :compte_uti)";
    $SQLparams = Array(':compte_uti' => $globalData['params']['user']['compte_uti'],
        ':pass_uti' => hash('sha256',$globalData['params']['user']['pass_uti']),
        ':mail_uti' => $globalData['params']['user']['mail_uti'],
        ':nom_uti' => $globalData['params']['user']['nom_uti'],
        ':prenom_uti' => $globalData['params']['user']['prenom_uti'],
        ':dateInscription_uti' => $dateCreation,
        ':dateValidation_uti' => $dateValidation,
        ':codeValidation_uti' => $codeValidation,
        ':comGroupe_uti' => ($globalData['params']['user']['comGroupe_uti'] ? 1 : 0));
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // récupérer id_uti
    $SQLrequete = "SELECT id_uti
FROM \${prefixe}utilisateur
WHERE \${prefixe}utilisateur.compte_uti = :compte_uti";
    $SQLparams = Array(':compte_uti' => $globalData['params']['user']['compte_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    $id_uti = $tabResult[0]['id_uti'];
    $globalData['message']['response']['id_uti'] = $id_uti;
    $globalData['message']['response']['compte_uti'] = $globalData['params']['user']['compte_uti'];
    $globalData['params']['user']['id_uti'] = $id_uti;
    
    // Ajouter au groupe
    $SQLrequete = "INSERT INTO \${prefixe}gro_uti(id_gro, id_uti, princ_gro_uti)
SELECT \${prefixe}groupe.id_gro,:id_uti, 1
from \${prefixe}groupe
where \${prefixe}groupe.cd_gro = :cd_gro";
    $SQLparams = Array(':cd_gro' => $pConfig['defaultGroupe'], ':id_uti'=>$id_uti);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);    

    // si c'est une création, envoyer un mail
    if($globalData['context'] == 'new')
    {
      $globalData['utilisateur']['id_uti'] = $id_uti;
      
      // récupérer le modèle HTML pour le mail
      $SQLrequete = "select modele_mhl
from \${prefixe}modelehtml
where code_mhl = '".$pConfig['modeleMail_creationCompte']."'";
      $SQLparams = Array();
      $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

      // si il n'existe pas
      if(count($tabResult) != 1)
      {
        $globalData['errCode'] = "ERR_GEN_02";
        $globalData['errLib'] = "Le modèle HTML du mail de création de compte est introuvable";
        throw new Exception('ERREUR');
      }
      
      // construire le tableau de données
      $donneesMail = array(
          "compte_uti"=> $globalData['params']['user']['compte_uti'],
          "codeActivation"=> $codeValidation,
          "lienValidation"=> '/index.html?action=validLink&id=' . md5($globalData['params']['user']['compte_uti'], false) . md5($globalData['params']['user']['mail_uti'], false) . $codeValidation
      );
              
      // fusionner le mail 
      $donneesMail = array_merge($donneesMail, $pConfig);
      $fusion = new FusionJsonHtml($donneesMail, $tabResult[0]['modele_mhl']);
      $htmlBody = $fusion->process();
      
      envoyerMail($globalData['params']['user']['mail_uti'], $pConfig['site_nom'] . " : Confirmation d'inscription", $htmlBody);
    }
  }
  // si demande de renvoi du code de validation
  if($globalData['context'] == 'resendCode')
  {
    
    $SQLrequete = "SELECT \${prefixe}utilisateur.*
FROM \${prefixe}utilisateur
WHERE \${prefixe}utilisateur.compte_uti = :compte_uti
AND \${prefixe}utilisateur.id_uti = :id_uti";
    $SQLparams = Array(':compte_uti' => $globalData['params']['user']['compte_uti'],
        ':id_uti' => $globalData['params']['user']['id_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    if(count($tabResult) == 0)
    {
        $globalData['errCode'] = "ERR_SEC_03";
        $globalData['errLib'] = "L'utilisateur non trouvé";
        throw new Exception('ERREUR_SEC');
    }
    
    $user = $tabResult[0];
    
    // Régénérer un nouveau code
    $char = 'abcdefghijklmnopqrstuvwxyz0123456789';
    $codeValidation = str_shuffle($char);
    $codeValidation = substr($codeValidation,0, 8);

    // Mettre à jour l'utilisateur
    $SQLrequete = "UPDATE \${prefixe}utilisateur
SET codeValidation_uti = :codeValidation_uti
where id_uti = :id_uti";
    $SQLparams = Array(':id_uti' => $user['id_uti'],
        ':codeValidation_uti' => $codeValidation);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);    

    // récupérer le modèle HTML pour le mail
    $SQLrequete = "select modele_mhl
from \${prefixe}modelehtml
where code_mhl = '".$pConfig['modeleMail_renvoiActivation']."'";
    $SQLparams = Array();
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // si il n'existe pas
    if(count($tabResult) != 1)
    {
      $globalData['errCode'] = "ERR_GEN_02";
      $globalData['errLib'] = "Le modèle HTML du mail de renvoi de code d'activation est introuvable";
      throw new Exception('ERREUR');
    }

    // construire le tableau de données
    $donneesMail = array(
        "compte_uti"=> $user['compte_uti'],
        "codeActivation"=> $codeValidation,
        "lienValidation"=> '/index.html?action=validLink&id=' . md5($user['compte_uti'], false) . md5($user['mail_uti'], false) . $codeValidation
    );


    // fusionner le mail 
    $donneesMail = array_merge($donneesMail, $pConfig);
    $fusion = new FusionJsonHtml($donneesMail, $tabResult[0]['modele_mhl']);
    $htmlBody = $fusion->process();

    envoyerMail($user['mail_uti'], $pConfig['site_nom'] . " : Nouveau code de validation", $htmlBody);
    $globalData['params']['user']['id_uti'] = $user['id_uti'];
    $globalData['utilisateur']['id_uti'] = $user['id_uti'];
  }
  
  // si demande de récupération de mot de passe
  if($globalData['context'] == 'recupPass')
  {
    // Récupérer l'utilisateur avec l'adresse mail
    $SQLrequete = "SELECT \${prefixe}utilisateur.*
FROM \${prefixe}utilisateur
WHERE \${prefixe}utilisateur.mail_uti = :mail_uti";
    $SQLparams = Array(':mail_uti' => $globalData['params']['user']['mail_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    // Réaliser les actions suivante uniquement si utilisateur trouvé
    if(count($tabResult) == 1)
    {
      $user = $tabResult[0];
      
      // créer un nouveau code de validation
      $char = 'abcdefghijklmnopqrstuvwxyz0123456789';
      $codeValidation = str_shuffle($char);
      $codeValidation = substr($codeValidation,0, 8);
      
      // Mettre à jour l'utilisateur
      $SQLrequete = "UPDATE \${prefixe}utilisateur
SET codeValidation_uti = :codeValidation_uti
where id_uti = :id_uti";
      $SQLparams = Array(':id_uti' => $user['id_uti'],
          ':codeValidation_uti' => $codeValidation);
      $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);    

      // récupérer le modèle HTML pour le mail
      $SQLrequete = "select modele_mhl
from \${prefixe}modelehtml
where code_mhl = '".$pConfig['modeleMail_recupPass']."'";
      $SQLparams = Array();
      $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

      // si il n'existe pas
      if(count($tabResult) != 1)
      {
        $globalData['errCode'] = "ERR_GEN_02";
        $globalData['errLib'] = "Le modèle HTML du mail de récupération de mot de passe est introuvable";
        throw new Exception('ERREUR');
      }

      // construire le tableau de données
      $donneesMail = array(
          "compte_uti"=> $user['compte_uti'],
          "lienRecup"=> '/index.html?action=recupPass&id=' . md5($user['compte_uti'], false) . md5($user['mail_uti'], false) . $codeValidation
      );


      // fusionner le mail 
      $donneesMail = array_merge($donneesMail, $pConfig);
      $fusion = new FusionJsonHtml($donneesMail, $tabResult[0]['modele_mhl']);
      $htmlBody = $fusion->process();

      envoyerMail($user['mail_uti'], $pConfig['site_nom'] . " : Demande récupération mot de passe", $htmlBody);
      $globalData['params']['user']['id_uti'] = $user['id_uti'];
      $globalData['utilisateur']['id_uti'] = $user['id_uti'];
    }

  }
  // si demande de changement de mot de passe
  if($globalData['context'] == 'changePass')
  {
    // Vérifier la cohérence du code transmis
    $code = $globalData['params']['user']['idCode'];
    // Récupérer les informations utilisateur
    $SQLrequete = "SELECT \${prefixe}utilisateur.*
from \${prefixe}utilisateur
where concat(md5(\${prefixe}utilisateur.compte_uti), md5(\${prefixe}utilisateur.mail_uti), \${prefixe}utilisateur.codeValidation_uti) = :code";
    $SQLparams = Array(':code' => $code);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // si il n'existe pas
    if(count($tabResult) != 1)
    {
      $globalData['errCode'] = "ERR_SEC_03";
      $globalData['errLib'] = "Le code d'identification transmis est incorrect";
      throw new Exception('ERREUR_SEC');
    }
    
    $user = $tabResult[0];
    
    // Mettre à jour l'utilisateur
    $SQLrequete = "UPDATE \${prefixe}utilisateur
SET pass_uti = :pass_uti
where id_uti = :id_uti";
    $SQLparams = Array(':id_uti' => $user['id_uti'],
        ':pass_uti' => hash('sha256',$globalData['params']['user']['pass_uti']));
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);    
    
    $globalData['message']['response']['compte_uti'] = $user['compte_uti'];
    $globalData['params']['user']['id_uti'] = $user['id_uti'];
    $globalData['utilisateur']['id_uti'] = $user['id_uti'];
  }
  
  // si changement de mot de passe
  if($globalData['context'] == 'forceChangePass')
  {
    
    // Vérifier que le compte n'existe pas
    $SQLrequete = "SELECT 1
FROM \${prefixe}utilisateur
WHERE \${prefixe}utilisateur.id_uti = :id_uti";
    $SQLparams = Array(':id_uti' => $globalData['params']['user']['id_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // si il existe
    if(count($tabResult) == 0)
    {
      $globalData['errCode'] = "ERR_FCN";
      $globalData['errLib'] = "Ce compte n'existe pas";
      throw new Exception('ERREUR_FCN');
    }
    
    $SQLrequete = "update \${prefixe}utilisateur
set pass_uti = :pass_uti
where id_uti = :id_uti";
    $SQLparams = Array(':id_uti' => $globalData['params']['user']['id_uti'],
            ':pass_uti' => hash('sha256',$globalData['params']['user']['pass_uti']));
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  }
  
    // si demande de changement de mot de passe
  if($globalData['context'] == 'desabo')
  {
    // Vérifier la cohérence du code transmis
    $code = $globalData['params']['user']['idCode'];
    $cle = md5(crypt(strtoupper($globalData['params']['user']['mail_uti']), '$5$rounds=6500$1Px98aW8TyS6mJ4u'));

    if($code != $cle){
      $globalData['errCode'] = "ERR_FCN";
      $globalData['errLib'] = "Le mail saisie ne correspond pas au destinataire du mail : " .$cle ;
      throw new Exception('ERREUR_FCN');
    }
    
    // Récupérer les informations utilisateur
    $SQLrequete = "SELECT \${prefixe}utilisateur.*
from \${prefixe}utilisateur
where mail_uti = :mail_uti";
    $SQLparams = Array(':mail_uti' => $globalData['params']['user']['mail_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // si il n'existe pas
    if(count($tabResult) != 1)
    {
      $globalData['errCode'] = "ERR_SEC_03";
      $globalData['errLib'] = "Aucun utilisateur trouvé";
      throw new Exception('ERREUR_SEC');
    }
    
    $user = $tabResult[0];
    
    // Mettre à jour l'utilisateur
    $SQLrequete = "UPDATE \${prefixe}utilisateur
SET comGroupe_uti = 0
where id_uti = :id_uti";
    $SQLparams = Array(':id_uti' => $user['id_uti']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);    
    
    $globalData['message']['response']['mail_uti'] = $user['mail_uti'];
    $globalData['params']['user']['id_uti'] = $user['id_uti'];
    $globalData['utilisateur']['id_uti'] = $user['id_uti'];

  }
  
  //Ajouter historique action
  ajouter_actionhisto($globalData['action'], $globalData['context'], "\${prefixe}utilisateur", 'id_uti', $globalData['params']['user']['id_uti']);

}