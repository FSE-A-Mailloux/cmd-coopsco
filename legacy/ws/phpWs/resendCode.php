<?php

global $tabWebServices;
$tabWebServices['resendCode'] = [false]; // [accredité]

function ws_resendCode($request)
{
  // récupération des variables globale
  global $globalData;
  
  $id_uti = $globalData['params']['id_uti'];
  $compte_uti = $globalData['params']['compte_uti'];

  // Récupérer les informations utilisateur
  $SQLrequete = "SELECT \${prefixe}utilisateur.*
from \${prefixe}utilisateur
where compte_uti = :compte_uti
and id_uti = :id_uti";
  $SQLparams = Array(':compte_uti' => $compte_uti, ':id_uti' => $id_uti);
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

  // si il n'existe pas
  if(count($tabResult) != 1)
  {
    $globalData['errCode'] = "ERR_GEN_02";
    $globalData['errLib'] = "Utilisation non conforme";
    throw new Exception('ERREUR_SEC');
  }
  
  $mail_uti = $tabResult[0]['mail_uti'];
  $compte = $tabResult[0]['compte_uti'];
  
  // regénérer un nouveau code
  $char = 'abcdefghijklmnopqrstuvwxyz0123456789';
  $codeValidation = str_shuffle($char);
  $codeValidation = substr($codeValidation,0, 8);
  
  // remettre le timestamp
  $SQLrequete = "update \${prefixe}utilisateur
set codeValidation_uti = :codeValidation_uti
where id_uti = :id_uti";
  $SQLparams = Array(':id_uti' => $id_uti, ':codeValidation_uti' => $codeValidation);
  $tabResult = $globalData['db']->prepLancerExcep($SQLrequete, $SQLparams, $globalData['errCode'], $globalData['errLib']);

  $url = $pConfig['site_url'] . '/index.html?action=validLink&id=' . md5($compte, true) . md5($mail_uti, true) . $codeValidation;
  
  $content = file_get_contents(SITE_BASE_PATH . 'mail'.DIRECTORY_SEPARATOR.'template_01.html');
  $content = str_replace("{titre}", "Nouveau code de validation", $content);
  $content = str_replace("{message}", file_get_contents(SITE_BASE_PATH . 'mail'.DIRECTORY_SEPARATOR.'newValidationCode.html'), $content);
  $content = str_replace("{urlsite}", $pConfig['site_url'], $content);
  $content = str_replace("{codeActivation}", $codeValidation, $content);
  $content = str_replace("{lienActivation}", $url, $content);

  envoyerMail($mail_uti, $pConfig['site_nom'] . " : Nouveau code de validation", $content);

}