/**
 * Déclaration du module compListMod
 */
angular.module('modeleHtmlListMod',[
  'servicesMod'
])
.controller('modeleHtmlListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
  function ($scope, dataStorage, appFunctions, modalWindow) {
    
    var nomComp = 'modeleHtmlList';
    var compGest = 'modeleHtmlGest';
    var requestList = 'modeleHtmlList';
    var alertSelect = 'Vous devez sélectionner un modèle';
    var questionSuppr = 'Etes-vous sur de vouloir supprimer ce modèle?';
    var wsManage = 'modeleHtmlManage';
   
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
          appFunctions.saveSelectedRow("code_mhl", row.code_mhl);
          appFunctions.loadComponent(compGest, {ctx:'mod', data:row});
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
        requestList,
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
      appFunctions.loadComponent(compGest, {ctx:'add'});
    };
    
    var modifyRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, alertSelect);
      if(row){
        appFunctions.saveSelectedRow("code_mhl", row.code_mhl);
        appFunctions.loadComponent(compGest, {ctx:'mod', data:row});
      }
    };
    
    var deleteRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, alertSelect);
      if(row){
        var modalOptions = {
          closeButtonText: 'Non',
          actionButtonText: 'Oui',
          headerText: 'Confirmation',
          bodyText: questionSuppr
        };
        modalWindow.showModal({}, modalOptions).then(function (result){
          appFunctions.post(wsManage,
            'del',
            {request:{id_req:row.id_mhl}},
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
    appFunctions.ajouterFonctionsComp(nomComp, {
      NOUVEAU:addRow,
      MODIFIER:modifyRow,
      SUPPRIMER:deleteRow
    });


    $scope.rowTitle = [
      {
        name:'type_mhl',
        lib:'Type',
        type:'text',
        format:'' 
      },{
        name:'code_mhl',
        lib:'Code',
        type:'text',
        format:'' 
      },{
        name:'lib_mhl',
        lib:'Description',
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


