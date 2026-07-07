/**
 * Déclaration du module compListMod
 */
angular.module('moduleListMod',[
  'servicesMod'
])
.controller('moduleListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow',
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    //$scope.rowCollection = [];
    //$scope.displayedCollection = [];
    
    
    $scope.doubleclick = function(row){
      if(row){
        appFunctions.saveSelectedRow("cd_mod", row.cd_mod);
        appFunctions.loadComponent('moduleParam', {ctx:'mod', data:row});
      }
    };

    var getList = function(){
      appFunctions.post('execRequest',
        'moduleList',
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
    
    var toParameter = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner un module');
      if(row){
        appFunctions.saveSelectedRow("cd_mod", row.cd_mod);
        appFunctions.loadComponent('moduleParam', {ctx:'mod', data:row});
      }
    };
    
    var toUninstall = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner un module');
      if(row){
        if(!verifVerrou(row)){
          var modalOptions = {
            closeButtonText: 'Non',
            actionButtonText: 'Oui',
            headerText: 'Confirmation',
            bodyText: 'Etes-vous sur de vouloir désinstaller ce module?'
          };
          modalWindow.showModal({}, modalOptions).then(function (result){
            appFunctions.post('moduleManage',
              'unistall',
              {com:{cd_com:row.cd_com}},
              function(data) {
                getList();
              },
              function(response) {

              }
            );
          }).catch(angular.noop);
        }
      }
    };
    
    var toInstall = function(){

    };
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('moduleList', {
      INSTALL:toInstall,
      UNINSTALL:toUninstall,
      PARAM:toParameter
    });


    $scope.rowTitle = [
      {
        name:'cd_mod',
        lib:'Code',
        type:'text',
        format:'' 
      },{
        name:'lib_mod',
        lib:'Libellé',
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


