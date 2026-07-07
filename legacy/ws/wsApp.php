<?php
  ob_start();
  ini_set('session.cookie_httponly',1);
  ini_set('session.use_only_cookies',1);
  set_time_limit(60);
  require_once('configuration.php');
  require_once('./phpClasses/class.DataBase.php');
  require_once('./phpClasses/appMail.php');
  
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
  
  function fatal_handler() {
    global $globalData;
    
    $globalData['errLib'] = error_get_last();

    if( $globalData['errLib'] !== NULL) {
      $globalData['errCode'] = "ERR_FATAL";
      throw new Exception('ERREUR');
    }
  }
  
  function log_error($errno, $errstr, $errfile, $errline){
    global $globalData;
    global $pConfig;
    
    $debug = false;
    if(array_key_exists( 'site_debug' , $pConfig )){
      $debug = $pConfig['site_debug'];
    }
    $globalData['errCode'] = $errno;
    if($debug){
      $globalData['errLib'] = $errstr . "\n- fichier : " . $errfile . " ligne " . $errline;
    }else{
      $globalData['errLib'] = "Erreur serveur interne";
    }
    throw new Exception('ERREUR');
  }
  
  register_shutdown_function( "fatal_handler" );
  set_error_handler( "log_error" );
  
  // Fonction de gestion d'ajout actionhisto
  function ajouter_actionhisto($cdAction_aho, $ctxAction_aho, $tableRef_aho, $colonneRef_aho, $idRef_aho){
    global $globalData;
    
    $SQLrequete = "insert into \${prefixe}actionhisto(id_uti, cdAction_aho, ctxAction_aho, dateheure_aho, tableRef_aho, colonneRef_aho, idRef_aho)
values(:id_uti, :cdAction_aho, :ctxAction_aho, sysdate(), :tableRef_aho, :colonneRef_aho, :idRef_aho)";
    $SQLparams = Array(
        ':id_uti' => $globalData['utilisateur']['id_uti'],
        ':cdAction_aho' => $cdAction_aho,
        ':ctxAction_aho' => $ctxAction_aho,
        ':tableRef_aho' => $tableRef_aho,
        ':colonneRef_aho' => $colonneRef_aho,
        ':idRef_aho' => $idRef_aho
    );
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
  }
  
  // fonction permettant de formater des liste et éviter les injections SQL
  function formater_liste($liste, $estUneChaine = false){
    $listeFormate = "";
    $tabValeur = explode(",", $liste);
    
    // Pour chaque valeur
    foreach ($tabValeur as $id => $valeur){
      $valeur = trim($valeur, " '");
      if($estUneChaine){
        $valeur = "'". str_replace("'", "''",$valeur)."'";
      }
      if($listeFormate !== ""){
        $listeFormate .= ",";
      }
      $listeFormate .= $valeur;
    }
    
    return $listeFormate;
  }
          
  
  
  header( 'content-type: text/html; charset=utf-8' );
  
  define('SITE_BASE_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
  
  setlocale(LC_TIME, 'fr_FR.utf8','fra');

  // Variable permettant de récupérer le service chargé
  $tabWebServices = array();

  try
  {
    if(isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") === false)
    {
      $globalData['errCode'] = "ERR_GEN_01";
      $globalData['errLib'] = "Utilisation non conforme";
      throw new Exception('ERREUR_SEC');
    }
    
    // Tranformer le flux json en tableau php
    $request = (array) json_decode(trim(file_get_contents('php://input')), true);
    
    // si une des données obligatoire n'est pas transmise
    if(!array_key_exists('action', $request) || !array_key_exists('token', $request) || !array_key_exists('params', $request)){
      $globalData['errCode'] = "ERR_GEN_02";
      $globalData['errLib'] = "Utilisation non conforme";
      throw new Exception('ERREUR_SEC');
    }
    
    $globalData['action'] = $request['action'];
    $globalData['token'] = $request['token'];
    $globalData['params'] = $request['params'];
    $globalData['context'] = $request['context'];


    // Inclure le fichier PHP utile à l'action
    include 'phpWs/'.$globalData['action'].'.php';
    
    // Vérifier que le webservice est défini
    if(!array_key_exists($globalData['action'], $tabWebServices)) {
      $globalData['errCode'] = "ERR_GEN_03";
      $globalData['errLib'] = "webService non définit";
      throw new Exception('ERREUR_SEC');
    }

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
    
    //var_dump($pConfig);

    // Supprimer les tokens caduque
    $tabResult = $globalData['db']->prepLancerExcep( "delete from \${prefixe}token where TIMESTAMPDIFF(SECOND, date_tok, now()) > :timeout",
            Array(':timeout' => $pConfig['tokenTimeout']),
            $globalData['errCode'],
            $globalData['errLib']);
    
    // si token transmis
    if($globalData['token'] != "")
    {
      // vérifier que le token existe toujours en récupérant les info du compte
      $tabResult = $globalData['db']->prepLancerExcep("SELECT \${prefixe}utilisateur.*
from \${prefixe}token, \${prefixe}utilisateur
where token_tok = :token
and \${prefixe}utilisateur.id_uti = \${prefixe}token.id_uti",
            Array(':token' => $globalData['token']),
            $globalData['errCode'],
            $globalData['errLib']);
      // si il n'existe pas
      if(count($tabResult) == 0)
      {
        $globalData['errCode'] = "ERR_TOK_01";
        $globalData['errLib'] = "Token expiré";
        throw new Exception('ERREUR_EXPIRE');
      }
      
      $globalData['utilisateur'] = $tabResult[0];
      $pConfig['utilisateur'] = $tabResult[0];
      
      // remettre le timestamp
      $tabResult = $globalData['db']->prepLancerExcep("update \${prefixe}token
set date_tok = :date_tok
where token_tok = :token",
            Array(':token' => $globalData['token'], ':date_tok' => date("Y-m-d H:i:s")),
            $globalData['errCode'],
            $globalData['errLib']);
    }

    // si le composant est soumis a accréditation
    if($tabWebServices[$globalData['action']][0]){
      $function = $globalData['action'];
      if($globalData['context'] != ""){
        $function .= ':'. $globalData['context'];
      }

      // vérifier que le token existe toujours et que l'utilisateur est autorisé à utiliser cette fonction
      $tabResult = $globalData['db']->prepLancerExcep("SELECT distinct 1
FROM \${prefixe}autorisation, \${prefixe}gro_uti, \${prefixe}token
WHERE \${prefixe}autorisation.type_acc = 'FON'
AND \${prefixe}autorisation.code_acc = :fonction
AND \${prefixe}gro_uti.id_gro = \${prefixe}autorisation.id_gro
AND \${prefixe}token.id_uti = \${prefixe}gro_uti.id_uti
and \${prefixe}token.token_tok = :token
union
SELECT distinct 1
FROM \${prefixe}autorisation, \${prefixe}groupe
WHERE \${prefixe}autorisation.type_acc = 'FON'
AND \${prefixe}autorisation.code_acc = :fonction
AND '' = :token
AND \${prefixe}groupe.id_gro = \${prefixe}autorisation.id_gro
and \${prefixe}groupe.cd_gro = 'NC'",
            Array(':fonction' => $function,':token' => $globalData['token']),
            $globalData['errCode'],
            $globalData['errLib']);
      
      // si il n'existe pas
      if(count($tabResult) == 0)
      {
        $globalData['errCode'] = "ERR_AUT_02";
        $globalData['errLib'] = "Vous n'êtes pas autorisé à utiliser cette action [". $function ."]";
        throw new Exception('ERREUR'); //_SEC
      }
    }
    
    
    // Lancement dynamique de la fonction
    $fonctionWs = 'ws_' .$globalData['action'];
    $fonctionWs($request);

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
    
    try
    {
      // si différent d'erreur fonctionnel
      if($globalData['message']['statusCode'] === "Error" || $globalData['message']['statusCode'] === "ErrorSecurity"){
        if(!isset($globalData['utilisateur']['id_uti'])){
          $globalData['utilisateur']['id_uti'] = 0;
        }
        $tabResult = $globalData['db']->prepLancerExcep("insert into \${prefixe}anomalie(id_uti, date_ano, action_ano, contexte_ano, erreur_ano)
values(:id_uti, :date_ano, :action_ano, :contexte_ano, :erreur_ano)",
            Array(
                ':id_uti' => $globalData['utilisateur']['id_uti'],
                ':date_ano' => date("Y-m-d H:i:s"),
                ':action_ano' => $globalData['action'],
                ':contexte_ano' => $globalData['action'],
                ':erreur_ano' => $globalData['message']['statusMessage']
            ),
            $globalData['errCode'],
            $globalData['errLib']);
      }
    } catch (Exception $ex) {

    }
  }

  //echo json_encode($globalData['message'], JSON_NUMERIC_CHECK);
  header('Content-type:application/json;charset=utf-8');
  echo json_encode($globalData['message']);
  ob_end_flush();
?>

