/**
 * Déclaration du module compListMod
 */
angular.module('functionListMod',[
  'servicesMod'
])
.controller('functionListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    //$scope.rowCollection = [];
    //$scope.displayedCollection = [];
    
    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
    $scope.doubleclick = function(row){
      if(row){
        appFunctions.saveSelectedRow("cd_fon", row.cd_fon);
        appFunctions.loadComponent('functionGest', {ctx:'mod', data:row});
      }
    };
    
    var getList = function(){
      appFunctions.post('execRequest',
        'functionList',
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
      appFunctions.loadComponent('functionGest', {ctx:'add'});
    };
    
    var modifyRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une fonction');
      if(row){
        appFunctions.saveSelectedRow("cd_fon", row.cd_fon);
        appFunctions.loadComponent('functionGest', {ctx:'mod', data:row});
      }
    };
    
    var deleteRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une fonction');
      if(row){
        var modalOptions = {
          closeButtonText: 'Non',
          actionButtonText: 'Oui',
          headerText: 'Confirmation',
          bodyText: 'Etes-vous sur de vouloir supprimer cette fonction?'
        };
        modalWindow.showModal({}, modalOptions).then(function (result){
          appFunctions.post('functionManage',
            'del',
            {fonction:{cd_fon:row.cd_fon}},
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
    appFunctions.ajouterFonctionsComp('functionList', {
      NOUVEAU:addRow,
      MODIFIER:modifyRow,
      SUPPRIMER:deleteRow
    });


    $scope.rowTitle = [
      {
        name:'cd_fon',
        lib:'Code',
        type:'text',
        format:'' 
      },{
        name:'lib_fon',
        lib:'Description',
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


