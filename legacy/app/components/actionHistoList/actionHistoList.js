/**
 * Déclaration du module compListMod
 */
angular.module('actionHistoListMod',[
  'servicesMod'
])
.controller('actionHistoListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow',
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    $scope.rowCollection = [];
    $scope.displayedCollection = [];
    
    
    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    
    var getList = function(){
      appFunctions.post('execRequest',
        'actionHistoList',
        {},
        function(data) {
          $scope.rowCollection = data.response.result;
          $scope.displayedCollection = [].concat($scope.rowCollection);
        },
        function(response) {

        }
      );
    };
    


    $scope.rowTitle = [
      {
        name:'Date',
        lib:'Date',
        type:'text',
        format:'' 
      },{
        name:'lib_com',
        lib:'Utilisateur',
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


