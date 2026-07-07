<?php
require_once('./vendor/autoload.php');

//require_once('./phpClasses/class.FusionJsonHtml.php');
//use Spipu\Html2Pdf\Html2Pdf;
global $tabWebServices;
$tabWebServices['mailManage'] = [true]; // [accredité]

function ws_mailManage($request)
{
  
  // récupération des variables globale
  global $globalData;
  global $pConfig;
  
  // selon le contexte
  if($globalData['context'] == 'previsualiser' || $globalData['context'] == 'envoyer'){

    // récupérer le modèle HTML pour le mail
    $SQLrequete = "select modele_mhl
from \${prefixe}modelehtml
where code_mhl = :code_mhl";
    $SQLparams = Array(':code_mhl' => $globalData['params']['mail']['code_mhl']);
    $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

    // si il n'existe pas
    if(count($tabResult) == 0)
    {
      $globalData['errCode'] = "ERR_MAIL_01";
      $globalData['errLib'] = "Le template mail [".$globalData['params']['mail']['code_mhl']."] n'existe pas";
      throw new Exception('ERREUR');
    }
    $htmlMail = $tabResult[0]["modele_mhl"];
    
    $dataFusion = Array("contenu"=>$globalData['params']['mail']['corps'] );
  
    // Réaliser la fusion
    
    $dataFusion = array_merge($dataFusion, $pConfig);

    $fusion = new FusionJsonHtml($dataFusion, $htmlMail);

    $htmlBody = $fusion->process();
  
    if($globalData['context'] == 'previsualiser'){
      $globalData['message']['response']['corpsHTML'] = $htmlBody;
    }else{
      
      
      $tabDest = [];
      // si le type de destinataire est un groupe
      if($globalData['params']['mail']['typeDest'] === "GRO"){
        $SQLrequete = "select \${prefixe}utilisateur.mail_uti
from \${prefixe}groupe
inner join \${prefixe}gro_uti ON \${prefixe}gro_uti.id_gro = \${prefixe}groupe.id_gro
inner join \${prefixe}utilisateur on \${prefixe}utilisateur.id_uti = \${prefixe}gro_uti.id_uti and \${prefixe}utilisateur.comGroupe_uti = 1
where \${prefixe}groupe.cd_gro = :cd_gro";
        $SQLparams = Array(':cd_gro' => $globalData['params']['mail']['destinataire']);
        $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
        if(count($tabResult) == 0)
        {
          $globalData['errCode'] = "ERR_MAIL_02";
          $globalData['errLib'] = "Les adresses mail des utilisateurs du group [".$globalData['params']['mail']['destinataire']."] n'ont pas été trouvées";
          throw new Exception('ERREUR');
        }
        foreach($tabResult as $row){
          array_push($tabDest, $row['mail_uti']);
        }
        
      // si le type de destinataire est un utilisateur
      }elseif($globalData['params']['mail']['typeDest'] === "UTI"){
        $SQLrequete = "select mail_uti
from \${prefixe}utilisateur
where compte_uti = :compte_uti";
        $SQLparams = Array(':compte_uti' => $globalData['params']['mail']['destinataire']);
        $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);
        if(count($tabResult) == 0)
        {
          $globalData['errCode'] = "ERR_MAIL_02";
          $globalData['errLib'] = "L'adresse mail de l'utilisateur[".$globalData['params']['mail']['destinataire']."] n'a pas été trouvée";
          throw new Exception('ERREUR');
        }
        array_push($tabDest, $tabResult[0]['mail_uti']);
        
      }else{
        $tabDest = explode ( ";" , $globalData['params']['mail']['destinataire']);
      }

      envoyerMail($tabDest, $globalData['params']['mail']['objet'], $htmlBody, [], $globalData['params']['mail']['masque']);
      
      //Ajouter historique action
      ajouter_actionhisto($globalData['action'], $globalData['context'], "", '', "");

    }
    
    
  }
 

}
