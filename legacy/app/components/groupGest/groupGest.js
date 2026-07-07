/**
 * Déclaration du module userMod
 */
angular.module('groupGestMod',[
  'servicesMod'
])
.controller('groupGestCtrl', ['$scope', 'dataStorage', 'appFunctions', '$timeout',
  function ($scope, dataStorage, appFunctions, $timeout) {
   
    // variables
    $scope.group = {
      id_gro: '',
      cd_gro:'',
      nom_gro:'',
      cd_com:'',
      niveau_gro:99
    };
    
    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
    $scope.valider = function(){
      appFunctions.post('groupManage',
        params.ctx,
        {group:$scope.group},
        function(data) {
          appFunctions.retourComposant();
        },
        function(response) {
          
        }
      );
    };

    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    //$scope.group.cd_gro = params.cd_gro;
    
    // si le contexte n'est pas définit
    if(!params.ctx){
      appFunctions.retourComposant();
    }
    $scope.contexte = params.ctx;

    // en cas de modification
    if(params.ctx === "mod"){
      // appeler le service de chargement des données d'un utilisateur
      appFunctions.post('execRequest',
        'getGroup',
        {id_gro:params.data.id_gro},
        function(data) {
          $scope.group = data.response.result[0];
          $scope.group.niveau_gro = Number($scope.group.niveau_gro);
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


