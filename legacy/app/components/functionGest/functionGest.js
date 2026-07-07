/**
 * Déclaration du module userMod
 */
angular.module('functionGestMod',[
  'servicesMod'
])
.controller('functionGestCtrl', ['$scope', 'dataStorage', 'appFunctions', '$timeout', 
  function ($scope, dataStorage, appFunctions, $timeout) {
   
    // variables
    $scope.fonction = {
      cd_fon: '',
      lib_fon:''
    };
    
    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
    $scope.valider = function(){
      appFunctions.post('functionManage',
        params.ctx,
        {fonction:$scope.fonction},
        function(data) {
          appFunctions.retourComposant();
        },
        function(response) {
          
        }
      );
    };

    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    
    // si le contexte n'est pas définit
    if(!params.ctx){
      appFunctions.retourComposant();
    }
    $scope.contexte = params.ctx;

    // en cas de modification
    if(params.ctx === "mod"){
      // appeler le service de chargement des données d'un utilisateur
      appFunctions.post('execRequest',
        'getFunction',
        {cd_fon:params.data.cd_fon},
        function(data) {
          $scope.fonction = data.response.result[0];
        },
        function(response) {
          $timeout(function(){appFunctions.retourComposant();}, 3000);
        }
      );
    }
    

  }
]);


