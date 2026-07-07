<?php

global $tabWebServices;
$tabWebServices['init'] = [false, false]; // [token valide, accredité]

function wsinit($request)
{
  // récupération des variables globale
  global $globalData;
  global $pConfig;

  // récupérer les paramètres de l'application pour les transmettre à la partie client
  $globalData['message']['response']['params'] = $pConfig['params'];
  
  //$globalData['message']['response']['composant'] = 'default';
  
  // Récupérer le menu du compodant default
  
  $globalData['message']['response']['menu'] = [
      ['Accueil', 'home', 'default'],
      ['Administration','cog', [
          ['Liste utilisateurs', 'user','gestionUtilisateur']
      ]]
  ];
}