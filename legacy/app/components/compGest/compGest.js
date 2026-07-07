/**
 * Déclaration du module userMod
 */
angular.module('compGestMod',[
  'servicesMod'
])
.controller('compGestCtrl', ['$scope', 'dataStorage', 'appFunctions', '$timeout',
  function ($scope, dataStorage, appFunctions, $timeout) {
   
    // variables
    $scope.comp = {
      cd_com: '',
      lib_com:'',
      verrou_com:0
    };

    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
    $scope.valider = function(){
      appFunctions.post('compManage',
        params.ctx,
        {com:$scope.comp},
        function(data) {
          appFunctions.retourComposant();
        },
        function(response) {
          
        }
      );
    };

    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    $scope.comp.cd_com = params.cd_com;
    
    // si le contexte n'est pas définit
    if(!params.ctx){
      appFunctions.retourComposant();
    }
    $scope.contexte = params.ctx;

    // en cas de modification
    if(params.ctx === "mod"){
      // appeler le service de chargement des données d'un utilisateur
      appFunctions.post('execRequest',
        'getComp',
        {cd_com:params.data.cd_com},
        function(data) {
          $scope.comp = data.response.result[0];
        },
        function(response) {
          $timeout(function(){appFunctions.retourComposant();}, 3000);
        }
      );
      

    }
    
    
    // Charger des fonction du composant dans l'appli
    //appFunctions.ajouterFonctionsComp('user', {testFnc:testFnc});
  }
]);


