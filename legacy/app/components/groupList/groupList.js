/**
 * Déclaration du module compListMod
 */
angular.module('groupListMod',[
  'servicesMod'
])
.controller('groupListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
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
        'groupList',
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
      appFunctions.loadComponent('groupGest', {ctx:'add'});
    };
    
    var modifyRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner un groupe');
      if(row){
        appFunctions.saveSelectedRow("id_gro", row.id_gro);
        appFunctions.loadComponent('groupGest', {ctx:'mod', data:row});
      }
    };
    
    var deleteRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner un groupe');
      if(row){
        var modalOptions = {
          closeButtonText: 'Non',
          actionButtonText: 'Oui',
          headerText: 'Confirmation',
          bodyText: 'Etes-vous sur de vouloir supprimer ce groupe?'
        };
        modalWindow.showModal({}, modalOptions).then(function (result){
          appFunctions.post('groupManage',
            'del',
            {group:{id_gro:row.id_gro}},
            function(data) {
              getList();
            },
            function(response) {

            }
          );
        }).catch(angular.noop);
      }
    };
    
    var toAutorisation = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner un composant');
      if(row){
        appFunctions.loadComponent('autorisationList', {data:row});
      }
    };
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('groupList', {
      NOUVEAU:addRow,
      MODIFIER:modifyRow,
      SUPPRIMER:deleteRow,
      toAutorisation:toAutorisation
    });


    $scope.rowTitle = [
      {
        name:'nom_gro',
        lib:'Nom',
        type:'text',
        format:'' 
      },{
        name:'cd_com',
        lib:'Composant attaché',
        type:'text',
        format:'' 
      },{
        name:'niveau_gro',
        lib:'Niveau',
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


