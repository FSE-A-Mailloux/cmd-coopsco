angular.module('fseCmdConfirmMod',[
  'servicesMod'
])
.controller('fseCmdConfirmCtrl', ['$scope', 'dataStorage', 'appFunctions',
  function ($scope, dataStorage, appFunctions) {
   
    // variables
    $scope.commande = {};

    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };

    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    $scope.commande = params.commande;
	
  }
]);
