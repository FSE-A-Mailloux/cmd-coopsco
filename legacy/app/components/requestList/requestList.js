/**
 * Déclaration du module compListMod
 */
angular.module('requestListMod',[
  'servicesMod'
])
.controller('requestListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    //$scope.rowCollection = [];
    //$scope.displayedCollection = [];
    
    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };

    $scope.doubleclick = function(row){
      if(!dataStorage.appDatas.searsh){
        if(row){
          appFunctions.saveSelectedRow("id_req", row.id_req);
          appFunctions.loadComponent('requestGest', {ctx:'mod', data:row});
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
        'requestList',
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
    
    var addRow = function(){
      appFunctions.loadComponent('requestGest', {ctx:'add'});
    };
    
    var modifyRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une requête');
      if(row){
        appFunctions.saveSelectedRow("id_req", row.id_req);
        appFunctions.loadComponent('requestGest', {ctx:'mod', data:row});
      }
    };
    
    var deleteRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une requête');
      if(row){
        var modalOptions = {
          closeButtonText: 'Non',
          actionButtonText: 'Oui',
          headerText: 'Confirmation',
          bodyText: 'Etes-vous sur de vouloir supprimer cette requête?'
        };
        modalWindow.showModal({}, modalOptions).then(function (result){
          appFunctions.post('requestManage',
            'del',
            {request:{id_req:row.id_req}},
            function(data) {
              getList();
            },
            function(response) {

            }
          );
        }).catch(angular.noop);
      }
    };

    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('requestList', {
      NOUVEAU:addRow,
      MODIFIER:modifyRow,
      SUPPRIMER:deleteRow
    });


    $scope.rowTitle = [
      {
        name:'code_req',
        lib:'Code',
        type:'text',
        format:'' 
      },{
        name:'lib_req',
        lib:'Description',
        type:'text',
        format:'' 
      },{
        name:'result_req',
        lib:'Code résultat',
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


