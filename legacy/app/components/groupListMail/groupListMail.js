/**
 * Déclaration du module compListMod
 */
angular.module('groupListMailMod',[
  'servicesMod'
])
.controller('groupListMailCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    $scope.rowCollection = [];
    $scope.displayedCollection = [];
    
    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };

    $scope.doubleclick = function(row){
      if(!dataStorage.appDatas.searsh){
        if(row){
          appFunctions.saveSelectedRow("id_gro", row.id_gro);
          appFunctions.loadComponent('groupGest', {ctx:'mod', data:row});
        }
      }else{
        dataStorage.appDatas.searshCloseGetRow(row);
      }
    };
    
    var getRow = function(){
      return appFunctions.getRowSelectTable($scope.displayedCollection);
    };
    
    var getList = function(){
      appFunctions.post('execRequest',
        'groupListMail',
        {},
        function(data) {
          $scope.selectedRow = appFunctions.loadSelectedRow();
          $scope.rowCollection = data.response.result;
          $scope.displayedCollection = [].concat($scope.rowCollection);
        },
        function(response) {

        }
      );
    };


    $scope.rowTitle = [
      {
        name:'nom_gro',
        lib:'Nom groupe',
        type:'text',
        format:'' 
      },{
        name:'nbUti_gro',
        lib:'Nombre utilisateur',
        type:'text',
        format:'' 
      }
    ];

    $scope.refreshList = function(){
      getList();
    };

    getList();
    
    if(dataStorage.appDatas.searsh){
      dataStorage.appDatas.searshGetRow = getRow;
    }
  }
]);


