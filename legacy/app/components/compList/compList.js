/**
 * Déclaration du module compListMod
 */
angular.module('compListMod',[
  'servicesMod'
])
.controller('compListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow',
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    //$scope.rowCollection = [];
    //$scope.displayedCollection = [];
   
    $scope.doubleclick = function(row){
      if(!dataStorage.appDatas.searsh){
        if(row){
          if(!verifVerrou(row)){
            appFunctions.saveSelectedRow("cd_com", row.cd_com);
            appFunctions.loadComponent('compGest', {ctx:'mod', data:row});
          }
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
        'compList',
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
    
    var verifVerrou = function(row){
      if(row.verrou_com === 1){
        var modalOptions = {
          closeButtonText: '',
          actionButtonText: 'Ok',
          headerText: 'Information',
          bodyText: 'Le composant est verrouillé'
        };
        modalWindow.showModal({}, modalOptions).catch(angular.noop);
        return true;
      }
      return false;
    };
    
    var addRow = function(){
      appFunctions.loadComponent('compGest', {ctx:'add'});
    };
    
    var modifyRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner un composant');
      if(row){
        if(!verifVerrou(row)){
          appFunctions.saveSelectedRow("cd_com", row.cd_com);
          appFunctions.loadComponent('compGest', {ctx:'mod', data:row});
        }
      }
      //appFunctions.loadComponent('user', {ctx:'mod', id:row.id_uti});
    };
    
    var deleteRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner un composant');
      if(row){
        if(!verifVerrou(row)){
          var modalOptions = {
            closeButtonText: 'Non',
            actionButtonText: 'Oui',
            headerText: 'Confirmation',
            bodyText: 'Etes-vous sur de vouloir supprimer ce composant?'
          };
          modalWindow.showModal({}, modalOptions).then(function (result){
            appFunctions.post('compManage',
              'del',
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
    
    var toMenu = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner un composant');
      if(row){
        if(!verifVerrou(row)){
          appFunctions.loadComponent('menuCompList', {data:row});
        }
      }
    };
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('compList', {
      NOUVEAU:addRow,
      MODIFIER:modifyRow,
      SUPPRIMER:deleteRow,
      toMenu:toMenu
    });


    $scope.rowTitle = [
      {
        name:'cd_com',
        lib:'Code',
        type:'text',
        format:'' 
      },{
        name:'lib_com',
        lib:'Libellé',
        type:'text',
        format:'' 
      },{
        name:'verrou_icon',
        lib:'',
        type:'icon',
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


