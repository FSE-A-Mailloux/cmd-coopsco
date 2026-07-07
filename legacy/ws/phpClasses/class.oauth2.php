<?php

class Oauth2
{

  private $_code = '';
  private $_clientId = '';
  private $_secretId = '';
  private $_urlAuth = '';
  private $_urlService = '';
  private $_parametre = [];
  
  public function __construct($code){
    global $globalData;
    
    // Récupérer les token 
    $SQLrequete = "SELECT *
FROM \${prefixe}oauth2
WHERE Code = :Code";
    $SQLparams = Array(':Code' => $code);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    if(count($tabResult) !== 1){
      $globalData['errCode'] = "ERR_OAUTH_01";
      $globalData['errLib'] = "La configuration oauth2 n'a pas été trouvé !";
      throw new Exception('ERREUR_FCN');
    }
    
    $this->_code = $code;
    $this->_clientId = $tabResult[0]['ClientId'];
    $this->_secretId = $tabResult[0]['SecretId'];
    $this->_urlAuth = $tabResult[0]['UrlAuth'];
    $this->_urlService = $tabResult[0]['UrlService'];
    $this->_parametre = (array) json_decode($tabResult[0]['Parametre'], true);

  }
  
  public function getAccesToken(){
    global $globalData;
    global $pConfig;
    
    $SQLrequete = "SELECT ifnull(CASE WHEN TabOauth2.AccesExpire > NOW() THEN TabOauth2.AccesToken ELSE null END, '') AS AccesToken
, ifnull(CASE WHEN TabOauth2.RefreshExpire > NOW() THEN TabOauth2.RefreshToken ELSE null END, '') AS RefreshToken
FROM \${prefixe}oauth2 as TabOauth2
WHERE TabOauth2.Code = :Code";
    $SQLparams = Array(':Code' => $this->_code);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
    
    if(count($tabResult) !== 1){
      $globalData['errCode'] = "ERR_OAUTH_02";
      $globalData['errLib'] = "La configuration a été supprimée !";
      throw new Exception('ERREUR');
    }
    
    $accesToken = $tabResult[0]['AccesToken'];
    $refreshToken = $tabResult[0]['RefreshToken'];
    if($refreshToken !== '' && $accesToken === ''){
      //$globalData['message']['response']['context'] = 'refresh ID';
      
      $oauth2Sql = new DataBase();
      $oauth2Sql->init($pConfig['db_server'], $pConfig['db_name'], $pConfig['db_user'], $pConfig['db_pass'], false, $pConfig['db_prefixe']);
      if($oauth2Sql->isErreur())
      {
        $globalData['errCode'] = "ERR_DB_01";
        $globalData['errLib'] = "Erreur lors de la connexion à la base de données : " . $oauth2Sql->errorInfo();
        throw new Exception('ERREUR_SEC');
      }
      
      // Réaliser une demande de nouveau AccesToken
      $postData =[
          'client_id' => $this->_clientId,
          'grant_type' => 'refresh_token',
          'refresh_token' => $refreshToken
      ];
      $options = array(
        CURLOPT_URL => $this->_urlAuth,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 2
      );
      $ch = curl_init();
      curl_setopt_array($ch, $options);
      $output = curl_exec($ch); 
      curl_close($ch);

      $json = json_decode($output, true);

      if(!$json['access_token'])
      {
        $globalData['errCode'] = "ERR_OAUTH_03";
        $globalData['errLib'] = "Erreur appel Api Token";
        throw new Exception('ERREUR');
      }
      $accesToken = $json['access_token'];
      $refreshToken = $json['refresh_token'];
      $expireIn = $json['expires_in'];
      
      // Enregistrer les informations en base de données
      $SQLrequete = "UPDATE \${prefixe}oauth2 
set AccesToken = :AccesToken
, AccesExpirationDelay = :AccesExpirationDelay
, AccesExpire = DATE_ADD(NOW(), INTERVAL (:AccesExpirationDelay - 30) second) 
WHERE Code = :Code";
      $SQLparams = Array(
          ':Code' => $this->_code,
          ':AccesToken' => $accesToken,
          ':AccesExpirationDelay' => intval($expireIn),
          ':RefreshToken' => $refreshToken
      );
      $tabResult = $oauth2Sql->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

      //$globalData['message']['response']['result'] = $json;


      
    }elseif($refreshToken === ''){
      //$globalData['message']['response']['context'] = 'secret Id';
      
      $oauth2Sql = new DataBase();
      $oauth2Sql->init($pConfig['db_server'], $pConfig['db_name'], $pConfig['db_user'], $pConfig['db_pass'], false, $pConfig['db_prefixe']);
      if($oauth2Sql->isErreur())
      {
        $globalData['errCode'] = "ERR_DB_01";
        $globalData['errLib'] = "Erreur lors de la connexion à la base de données : " . $oauth2Sql->errorInfo();
        throw new Exception('ERREUR_SEC');
      }
      
      // Réaliser une demande de nouveau RefreshToken
      $postData =[
          'client_id' => $this->_clientId,
          'client_secret' => $this->_secretId,
          'grant_type' => 'client_credentials'
      ];
      $options = array(
        CURLOPT_URL => $this->_urlAuth,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $postData,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 2
      );
      $ch = curl_init();
      curl_setopt_array($ch, $options);
      $output = curl_exec($ch); 
      curl_close($ch);

      $json = json_decode($output, true);

      if(!$json['access_token'])
      {
        $globalData['errCode'] = "ERR_OAUTH_03";
        $globalData['errLib'] = "Erreur appel Api Token";
        throw new Exception('ERREUR');
      }
      $accesToken = $json['access_token'];
      $refreshToken = $json['refresh_token'];
      $expireIn = $json['expires_in'];
      
      // Enregistrer les informations en base de données
      $SQLrequete = "UPDATE \${prefixe}oauth2 
set AccesToken = :AccesToken
, AccesExpirationDelay = :AccesExpirationDelay
, AccesExpire = DATE_ADD(NOW(), INTERVAL (:AccesExpirationDelay - 30) second) 
, RefreshToken = :RefreshToken
, RefreshExpire = DATE_ADD(NOW(), INTERVAL (RefreshExpirationDelay - 1) day)
WHERE Code = :Code";
      $SQLparams = Array(
          ':Code' => $this->_code,
          ':AccesToken' => $accesToken,
          ':AccesExpirationDelay' => intval($expireIn),
          ':RefreshToken' => $refreshToken
      );
      $tabResult = $oauth2Sql->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

      //$globalData['message']['response']['result'] = $json;
    }

    return $accesToken;
  }
  
  public function callApi($method, $endpoint, $dataApi){
    global $globalData;
    
    $token = $this->getAccesToken();
    $authorization = "Authorization: Bearer " .$token;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization ));
    curl_setopt($ch, CURLOPT_URL, $this->_urlService . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    if($method === "POST"){
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataApi));
    }elseif($method === "GET"){
      // rien à faire
    }else{
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dataApi));
    }
    
    
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    $output = curl_exec($ch);
    $info = curl_getinfo($ch);
    $error = curl_error($ch);
    //$globalData['message']['response']['httpResponse'] = $info;
    //$globalData['message']['response']['error'] = $error;
    curl_close($ch);

    $response = json_decode($output, true);
    //$globalData['message']['response']['callapi'] = $response;
    return $response;
  }
  
  public function getParametre() {
    return $this->_parametre;
  }
}