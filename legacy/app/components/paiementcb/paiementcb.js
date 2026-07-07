/**
 * Déclaration du module paiementcbMod
 */
angular.module('paiementcbMod',[
  'servicesMod'
])
.controller('paiementcbCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
  function ($scope, dataStorage, appFunctions, modalWindow) {

    $scope.contexte = "";

    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    $scope.contexte = params.contexte;
    
    window.scrollTo(0,0);
  }
]);
