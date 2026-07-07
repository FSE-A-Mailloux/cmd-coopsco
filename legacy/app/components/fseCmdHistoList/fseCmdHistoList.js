/**
 * Déclaration du module menuCompListMod
 */
angular.module('fseCmdHistoListMod',[
  'servicesMod'
])
.controller('fseCmdHistoListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    
    // fonctions
    var getList = function(){
      appFunctions.post('execRequest',
        'fseCmdHistoList',
        {id_cmd:params.data.id_cmd},
        function(data) {
          $scope.selectedRow = appFunctions.loadSelectedRow();
          $scope.rowCollection = data.response.result;
          $scope.displayedCollection = [].concat($scope.rowCollection);
        },
        function(response) {

        }
      );
    };

    // Code démarrage

    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    $scope.num_commande = params.data.num_cmd
    

    $scope.rowTitle = [
      {
        name:'utilisateur',
        lib:'Utilisateur',
        type:'text',
        format:'' 
      },{
        name:'dateAction',
        lib:'Horodatage',
        type:'text',
        format:'' 
      },{
        name:'lib_fon',
        lib:'Action',
        type:'text',
        format:'' 
      }
    ];

    $scope.refreshList = function(){
      getList();
    };

    getList();
    
  }
]);


