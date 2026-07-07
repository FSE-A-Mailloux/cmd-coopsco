/**
 * Déclaration du module fseFournisseurGestMod
 */
angular.module('fseFournisseurGestMod',[
  'servicesMod'
])
.controller('fseFournisseurGestCtrl', ['$scope', 'dataStorage', 'appFunctions', '$timeout', 
  function ($scope, dataStorage, appFunctions, $timeout) {

    // variables
    $scope.fse_fournisseur = {
      id_fou:-1,
      design_fou:'',
      adr1_fou:'',
      adr2_fou:'',
      adr3_fou:'',
      cp_fou:'',
      ville_fou:'',
      tel_fou:'',
      mail_fou:''
    };
    
    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
    $scope.valider = function(){
      appFunctions.post('fseFournisseurManage',
        params.ctx,
        {fse_fournisseur:$scope.fse_fournisseur},
        function(data) {
          appFunctions.retourComposant();
        },
        function(response) {
          
        }
      );
    };
    
    $scope.formatTel = function(pNum){
      return appFunctions.formatPhoneNumber(pNum);
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
        'getFseFournisseur',
        {id_fou:params.data.id_fou},
        function(data) {
          $scope.fse_fournisseur = data.response.result[0];
        },
        function(response) {
          $timeout(function(){appFunctions.retourComposant();}, 3000);
        }
      );
    }
    

  }
]);


