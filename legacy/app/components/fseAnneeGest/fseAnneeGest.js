/**
 * Déclaration du module userMod
 */
angular.module('fseAnneeGestMod',[
  'servicesMod', "ui.bootstrap"
])
.controller('fseAnneeGestCtrl', ['$scope', 'dataStorage', 'appFunctions', '$timeout', 'uibDateParser', '$filter',
  function ($scope, dataStorage, appFunctions, $timeout, uibDateParser, $filter) {

    // variables
    $scope.fse_annee = {
      id_ann: -1,
      lib_ann:'',
      dtbutoir_ann :'',
      prefix_ann:'',
      encours_ann:0,
      verrou_ann:0
    };
    
    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
    $scope.valider = function(){
      $scope.fse_annee.dtbutoir_ann = $filter('date')($scope.dtvalue, 'yyyy-MM-dd');
      appFunctions.post('fseAnneeManage',
        params.ctx,
        {fse_annee:$scope.fse_annee},
        function(data) {
          appFunctions.retourComposant();
        },
        function(response) {
          
        }
      );
    };
    
    // Utilisation du datepicker 
    $scope.dtopened = false;
    $scope.dtformat = 'dd/MM/yyyy';
    $scope.dtvalue = '';
    $scope.dateOptions = {
      formatYear: 'yy',
      startingDay: 1
    };
    $scope.dtopen = function(e) {
      e.preventDefault();
      e.stopPropagation();

      $scope.dtopened = true;
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
        'getFseAnnee',
        {id_ann:params.data.id_ann},
        function(data) {
          $scope.fse_annee = data.response.result[0];
          $scope.dtvalue = uibDateParser.parse($scope.fse_annee.dtbutoir_ann, 'yyyy-MM-dd');
        },
        function(response) {
          $timeout(function(){appFunctions.retourComposant();}, 3000);
        }
      );
    }
    

  }
]);


