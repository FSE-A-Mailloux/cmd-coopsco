angular.module('defaultFseFamilleMod',[
  'servicesMod',
  'chart.js'
])
.controller('defaultFseFamilleCtrl', ['$scope', 'dataStorage', 'appFunctions', '$timeout', 'modalWindow',
  function ($scope, dataStorage, appFunctions, $timeout, modalWindow) {
   
    
    $scope.getParams = function(){
      return dataStorage.params;
    };

    // fonctions
    var lierCommande = function(){
      appFunctions.post('fseCmdManage',
        'lier',
        {},
        function(data) {
          
        },
        function(response) {

        }
      );
    };
	

    
    // récupération information statistique
    lierCommande();
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('defaultFseFamille', {
      
    });

  }
]);


