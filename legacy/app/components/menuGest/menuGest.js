/**
 * Déclaration du module userMod
 */
angular.module('menuGestMod',[
  'servicesMod'
])
.controller('menuGestCtrl', ['$scope', 'dataStorage', 'appFunctions', 
  function ($scope, dataStorage, appFunctions) {
   
    // variables
    $scope.menu = {
      id_mco: -1,
      cd_com: '',
      nom_mco:'',
      position_mco:0,
      glyphicon_mco:'',
      typeOption_mco:'COM',
      cdComDest_mco:''
    };
    
    // fonctions
    $scope.valider = function(){
      appFunctions.post('menuManage',
        params.ctx,
        {menu:$scope.menu},
        function(data) {
          appFunctions.retourComposant();
        },
        function(response) {
          
        }
      );
    };

    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    $scope.titreComp = '['+ params.cd_com +'] ' + params.lib_com;
    $scope.menu.cd_com = params.cd_com;
    
    // si le contexte n'est pas définit
    if(!params.ctx){
      appFunctions.retourComposant();
    }
    $scope.contexte = params.ctx;

    // en cas de modification
    if(params.ctx === "mod"){
      // appeler le service de chargement des données d'un utilisateur
      appFunctions.post('execRequest',
        'getMenu',
        {id_mco:params.data.id_mco},
        function(data) {
          $scope.menu = data.response.result[0];
          $scope.menu.position_mco = Number($scope.menu.position_mco);
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


