<?php

  class DataBase
  {

    var $_erreur = false;
    var $_errorInfo = array();
    
    function init($pHost, $pDbName, $pDbLogin, $pDbPass)
    {
			try {
				$this->db = new PDO("mysql:host=".$pHost.";dbname=".$pDbName, $pDbLogin, $pDbPass);
				$this->db->exec('SET NAMES utf8');
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
      $sql_prepare = $this->db->prepare($pRequete);
      return [$sql_prepare,$pRequete];
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
        return $sql_prepare->fetchAll();
      else
        return null;
    }
    
    function isErreur()
    {
      return $this->_erreur;
    }
    
    function errorInfo()
    {
      return $this->_errorInfo;
    }
  }

?>