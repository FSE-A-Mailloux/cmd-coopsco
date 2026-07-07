<?php
  ob_start();
  ini_set('session.cookie_httponly',1);
  ini_set('session.use_only_cookies',1);
  set_time_limit(60);
  require_once('configuration.php');
  require_once('./phpClasses/class.DataBase.php');
  require_once('./phpClasses/appMail.php');
  
  require_once('./vendor/autoload.php');
  require_once('./phpClasses/class.FusionJsonHtml.php');
  
  header( 'content-type: text/html; charset=utf-8' );
  
  define('SITE_BASE_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
  
  setlocale(LC_TIME, 'fr_FR.utf8','fra');
  
  
  function fatal_handler() {
    global $globalData;
    
    $globalData['errLib'] = error_get_last();
    if(isset($globalData['errLib']['type'])) {
      header('HTTP/1.1 500 Internal Server Error');
      $globalData['errCode'] = "ERR_FATAL";
    }
    
    
  }
  register_shutdown_function( "fatal_handler" );
  
  // Définition de l'objet global
  $globalData = array(
    'message'=>array(
      'statusCode'=>'OK',
      'statusMessage'=>'',
      'response'=> []
    ),
    'errCode' => "",
    'errLib' => "",
    'db' => new DataBase(),
    'token' => '',
    'utilisateur' => [],
    'action' => "",
    'context' => ""
  );


  try
  {

    // initialiser la connexion à la base de données
    $globalData['db']->init($pConfig['db_server'], $pConfig['db_name'], $pConfig['db_user'], $pConfig['db_pass'], true, $pConfig['db_prefixe']);
    if($globalData['db']->isErreur())
    {
      $globalData['errCode'] = "ERR_DB_01";
      $globalData['errLib'] = "Erreur lors de la connexion à la base de données : " . $globalData['db']->errorInfo();
      throw new Exception('ERREUR_SEC');
    }
    
    // Récupérer les paramétres
    $tabResult = $globalData['db']->prepLancerExcep( "select paramServeur_par from \${prefixe}parametre",
            Array(),
            $globalData['errCode'],
            $globalData['errLib']);
    
    // désérialiser le résultat
    $moduleParam = (array) json_decode($tabResult[0]['paramServeur_par'], true);
    
    // Ajouter au paramètre de configuration
    $pConfig = array_merge($pConfig, $moduleParam);

    // Tranformer le flux json en tableau php
    $request = (array) json_decode(trim(file_get_contents('php://input')), true);
    
    if(!isset($request['eventType'])){
      $globalData['errCode'] = "ERR_NT_01";
      $globalData['errLib'] = "Notification non conforme";
      throw new Exception('ERREUR');
    }
    
    // selon la notification
    if($request['eventType'] === 'Payment'){
      if($request['data']['state'] === 'Authorized'){
        $id_cmd = intval($request['metadata']['id_cmd']);
        $CbCheckOutId = intval($request['data']['order']['id']);
        $montant = round(floatval($request['data']['amount'])/100, 2);
        
        // Récupérer la commande et vérifier si le règlement est déjà enregistré
        $SQLrequete = "select TabCommande.*
, TabReglement.id_cmdrglt
from \${prefixe}fse_commande as TabCommande
left join \${prefixe}fse_cmdrglt as TabReglement on TabReglement.id_cmd = TabCommande.id_cmd and TabReglement.CbCheckoutId = :CbCheckoutId
where TabCommande.id_cmd = :id_cmd";
        $SQLparams = Array(':id_cmd' => $id_cmd,
                ':CbCheckoutId' => $CbCheckOutId);
        $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

        if(count($tabResult) !== 1)
        {
          $globalData['errCode'] = "ERR_NTCB_01";
          $globalData['errLib'] = "La commande est introuvable";
          throw new Exception('ERREUR');
        }
        
        $commande = $tabResult[0];
        
        if($tabResult[0]['id_cmdrglt'] === null){
          $SQLrequete = "insert into \${prefixe}fse_cmdrglt (id_cmd, type_cmdrglt, montant_cmdrglt, date_cmdrglt, CbCheckoutId)
values(:id_cmd, 4, :montant_cmdrglt, :date_cmdrglt, :CbCheckoutId)";
          $SQLparams = Array(
            ':id_cmd' => $commande['id_cmd'],
            ':montant_cmdrglt' => $montant,
            ':date_cmdrglt' => date("Y-m-d H:i:s"),
            ':CbCheckoutId' => $CbCheckOutId
          );
          $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
          
          $SQLrequete = "update \${prefixe}fse_commande set dtvalid_cmd = :dtvalid_cmd where id_cmd = :id_cmd";
          $SQLparams = Array(':id_cmd' => $commande['id_cmd'], ':dtvalid_cmd' => date("Y-m-d H:i:s"));
          $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

          // récupérer le modèle HTML pour le mail
          $SQLrequete = "select modele_mhl
from \${prefixe}modelehtml
where code_mhl = 'MAIL_VALID_CMD_FSE'";
          $SQLparams = Array();
          $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

          // si il n'existe pas
          if(count($tabResult) != 1)
          {
            $globalData['errCode'] = "ERR_GEN_02";
            $globalData['errLib'] = "Le modèle HTML du mail de validation est introuvable";
            throw new Exception('ERREUR_PDF');
          }

          // réaliser la fusion HTML
          $dataFusion = array_merge($commande, $pConfig);
          $fusion = new FusionJsonHtml($dataFusion, $tabResult[0]['modele_mhl']);
          $htmlBody = $fusion->process();

          // envoyer le mail
          envoyerMail($commande['mail_cmd'], "FSE - CoopSco - Validation commande", $htmlBody);
        }
        
      }
    }

    


    $globalData['db']->commit();
  }
  catch(Exception $e)
  {
    $typeErr = $e->getMessage();
    if($typeErr == 'ERREUR_COMMIT'){
      $globalData['db']->commit();
      $typeErr = 'ERREUR';
    }else{
      $globalData['db']->rollback();
    }
    
    if($typeErr == 'ERREUR')
    {
      $globalData['message']['statusCode'] = 'Error';
      $globalData['message']['statusMessage'] = $globalData['errCode'] . " - " .$globalData['errLib'];
    }
    elseif($typeErr == 'ERREUR_FCN')
    {
      $globalData['message']['statusCode'] = 'ErrorFonctionnal';
      $globalData['message']['statusMessage'] = $globalData['errLib'];
    }
    elseif($typeErr == 'ERREUR_SEC')
    {
      $globalData['message']['statusCode'] = 'ErrorSecurity';
      $globalData['message']['statusMessage'] = $globalData['errCode'] . " - " .$globalData['errLib'];
    }
    elseif($typeErr == 'ERREUR_EXPIRE')
    {
      $globalData['message']['statusCode'] = 'ErrorTokenExpirate';
      $globalData['message']['statusMessage'] = 'Session expirée';
    }
    /*elseif($e->getMessage() == 'MAJ') 
    {
      $globalData['message']['Status'] = 'Update';
      $globalData['message']['Response']['Code'] = $ErrCode;
      $globalData['message']['Response']['Libelle'] = $ErrLib;
    }*/
    else
    {
      $globalData['message']['statusCode'] = 'ErrorSecurity';
      $globalData['message']['statusMessage'] = "ERR_GEN_00 - " .$typeErr;
    }
    // Passer un code http de erreur serveur interne
    header('HTTP/1.1 500 Internal Server Error');
  }

  //echo json_encode($globalData['message'], JSON_NUMERIC_CHECK);
  header('Content-type:application/json;charset=utf-8');
  echo json_encode($globalData['message']);
  ob_end_flush();