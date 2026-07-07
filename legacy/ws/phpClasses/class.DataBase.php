<?php

class DataBase
{

  var $_erreur = false;
  var $_errorInfo = array();
  var $_tabPrefixe = '';

  function init($pHost, $pDbName, $pDbLogin, $pDbPass, $pTransaction, $pTabPrefixe)
  {
    try {
      $this->db = new PDO("mysql:host=".$pHost.";dbname=".$pDbName, $pDbLogin, $pDbPass);
      $this->db->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, 0);
      
      $this->db->exec('SET NAMES utf8mb4');
      if($pTransaction)
      {
        $this->db->beginTransaction();
      }
      $this->_tabPrefixe = $pTabPrefixe;
    } catch (PDOException $e) {
      $this->_erreur = true;
      $this->_errorInfo = "Error!: " . $e->getMessage();
    }
  }

  function prepare($pCode, $pPrefixe = "")
  {
    switch($pCode)
    {
      case "TEST":
        $requete = "SELECT 1 from dual";
        break;

      default:
        $requete = "";
    }
      
    $sql_prepare = $this->db->prepare($requete);

    return [$sql_prepare,$requete];
  }
    
  function prepare_requete($pRequete)
  {
    $pRequete = str_replace('${prefixe}', $this->_tabPrefixe, $pRequete);
    $sql_prepare = $this->db->prepare($pRequete);
    return [$sql_prepare,$pRequete];
  }
  
  function prepLancerExcep($requete, $tabParam, &$errCode = null, &$errLib = null){
    $db_prepare = $this->prepare_requete($requete);
    $result = $this->lancer_requete(null, null, $tabParam, $db_prepare);
    if($this->isErreur())
    {
      $errCode = "ERR_DB_02";
      $errLib = "Erreur lors de la requête SQL : " . $db_prepare[1] . '   :   '. $this->errorInfo();
      throw new Exception('ERREUR');
    }
    return $result;
  }
  

  function lancer_requete_exception($pCode, $pPrefixe = "", $pTabArray = null, $pPreparation = null, &$errCode = null, &$errLib = null){
    $result = $this->lancer_requete($pCode, $pPrefixe, $pTabArray, $pPreparation);
    if($this->isErreur())
    {
      $errCode = "ERR_DB_02";
      $errLib = "Erreur lors de la requête SQL : " . $this->errorInfo();
      throw new Exception('ERREUR');
    }
    return $result;
  }
  
  function lancer_requete($pCode, $pPrefixe = "", $pTabArray = null, $pPreparation = null)
  {
    $this->_erreur = false;
    if( $pPreparation != null)
    {
      $sql_prepare = $pPreparation[0];
      $requete = $pPreparation[1];
    }
    else
    {
      $preparation = $this->prepare($pCode, $pPrefixe);
      $sql_prepare = $preparation[0];
      $requete = $preparation[1];
    }

    $sql_prepare->closeCursor();
    if($pTabArray != null)
      $this->_erreur = !$sql_prepare->execute($pTabArray);
    else
      $this->_erreur = !$sql_prepare->execute();

    if( $this->_erreur)
      $this->_errorInfo = $sql_prepare->errorInfo()[2];

    if( strpos(strtoupper($requete), "UPDATE") === false && strpos(strtoupper($requete), "INSERT") === false)
      return $sql_prepare->fetchAll(PDO::FETCH_ASSOC);
    else
      return null;
  }
  
  function getLastId()
  {
    return $this->db->lastInsertId();
  }
    
  function isErreur()
  {
    return $this->_erreur;
  }

  function errorInfo()
  {
    return $this->_errorInfo;
  }

  function commit(){
    if(isset($this->db))
      $this->db->commit();
  }
  function rollback(){
    if(isset($this->db))
      $this->db->rollback();
  }

    function getTabColumDefinition($pRequete, $pTabArray = null){
    $tabDefColumn = [];
    $pRequete = str_replace('${prefixe}', $this->_tabPrefixe, $pRequete);
    $sql_prepare = $this->db->prepare($pRequete);
    $sql_prepare->closeCursor();
    if($pTabArray != null)
      $this->_erreur = !$sql_prepare->execute($pTabArray);
    else
      $this->_erreur = !$sql_prepare->execute();
    //$select = $this->db->query($pRequete);
    foreach(range(0, $sql_prepare->columnCount() - 1) as $column_index)
    {
      array_push($tabDefColumn, $sql_prepare->getColumnMeta($column_index));
    }
    return $tabDefColumn;
  }
}

?>