/**
 * Déclaration du module userMod
 */
angular.module('fseStockGestMod',[
  'servicesMod'
])
.controller('fseStockGestCtrl', ['$scope', 'dataStorage', 'appFunctions', '$timeout', 
  function ($scope, dataStorage, appFunctions, $timeout) {

    // variables
    $scope.fse_stock = {
      id_art: -1,
      code_art:'',
      lib_art:'',
      nb_sto:0
    };

    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
    $scope.valider = function(){
      appFunctions.post('fseStockManage',
        params.ctx,
        {fse_stock:$scope.fse_stock},
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
        'getFseStock',
        {id_art:params.data.id_art},
        function(data) {
          $scope.fse_stock = data.response.result[0];
          $scope.fse_stock.nb_sto = Number($scope.fse_stock.nb_sto);
        },
        function(response) {
          $timeout(function(){appFunctions.retourComposant();}, 3000);
        }
      );
    }
  }
]);


