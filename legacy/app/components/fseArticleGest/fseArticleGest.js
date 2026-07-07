/**
 * Déclaration du module userMod
 */
angular.module('fseArticleGestMod',[
  'servicesMod'
])
.controller('fseArticleGestCtrl', ['$scope', 'dataStorage', 'appFunctions', '$timeout', 
  function ($scope, dataStorage, appFunctions, $timeout) {

    // variables
    $scope.fse_article = {
      id_art: -1,
      code_art:'',
      lib_art:'',
      marque_art:'',
      type_art:'CLA',
      ordre_art:0,
      id_fou:-1,
      cmdfam_art:0,
      stock_art:0,
      conso_art:0
    };
    
    $scope.liste_fou = [];
    
    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
    $scope.valider = function(){
      appFunctions.post('fseArticleManage',
        params.ctx,
        {fse_article:$scope.fse_article},
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
        'getFseArticle',
        {id_art:params.data.id_art},
        function(data) {
          $scope.fse_article = data.response.result[0];
          $scope.fse_article.ordre_art = Number($scope.fse_article.ordre_art);
          $scope.fse_article.cmdfam_art = Number($scope.fse_article.cmdfam_art);
          $scope.fse_article.stock_art = Number($scope.fse_article.stock_art);
          $scope.fse_article.conso_art = Number($scope.fse_article.conso_art);
        },
        function(response) {
          $timeout(function(){appFunctions.retourComposant();}, 3000);
        }
      );
    }
    
    // récupérer liste combo
      appFunctions.post('execRequest',
        'listFseFournisseur',
        {},
        function(data) {
          $scope.liste_fou = data.response.result;
        },
        function(response) {
          $timeout(function(){appFunctions.retourComposant();}, 3000);
        }
      );
    

  }
]);


