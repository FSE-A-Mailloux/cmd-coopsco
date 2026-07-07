/**
 * Déclaration du module userMod
 */
angular.module('fseArticlePrixGestMod',[
  'servicesMod'
])
.controller('fseArticlePrixGestCtrl', ['$scope', 'dataStorage', 'appFunctions', '$timeout', 
  function ($scope, dataStorage, appFunctions, $timeout) {

    // variables
    $scope.fse_articleprix = {
      id_art: -1,
      lib_art:'',
      periodes:[]
    };
    
    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
    $scope.valider = function(){
      appFunctions.post('fseArticlePrixManage',
        'mod',
        {fse_articleprix:$scope.fse_articleprix},
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
      // appeler le service de chargement des données
      appFunctions.post('execRequest',
        'getFseArticlePrix',
        {id_art:params.data.id_art},
        function(data) {
          $scope.fse_articleprix = data.response.article[0];
          $scope.fse_articleprix.periodes = data.response.periodes;
          for(var i = 0; i < $scope.fse_articleprix.periodes.length; i++) {
          $scope.fse_articleprix.periodes[i].prix_pafe = Number($scope.fse_articleprix.periodes[i].prix_pafe);
          $scope.fse_articleprix.periodes[i].lot_pafr = Number($scope.fse_articleprix.periodes[i].lot_pafr);
          $scope.fse_articleprix.periodes[i].prix_pafr = Number($scope.fse_articleprix.periodes[i].prix_pafr);
          }

        },
        function(response) {
          $timeout(function(){appFunctions.retourComposant();}, 3000);
        }
      );
    }
    

  }
]);


