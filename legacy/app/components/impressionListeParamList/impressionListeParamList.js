/**
 * Déclaration du module impressionListeParamListMod
 */
angular.module('impressionListeParamListMod',[
  'servicesMod'
])
.controller('impressionListeParamListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // Variables
    $scope.rowCollection = [];
    $scope.displayedCollection = [];
    $scope.selectedRow = {};
   
   
    $scope.doubleclick = function(row){
      if(!dataStorage.appDatas.searsh){
        if(row){
          appFunctions.saveSelectedRow("id_ilp", row.id_ilp);
          appFunctions.loadComponent('impressionListeParamGest', {ctx:'mod', data:row});
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
        'impressionListeParamList',
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
      appFunctions.loadComponent('impressionListeParamGest', {ctx:'add'});
    };
    
    var modifyRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner un paramétrage d\'impression de liste.');
      if(row){
          appFunctions.saveSelectedRow("id_ilp", row.id_ilp);
          appFunctions.loadComponent('impressionListeParamGest', {ctx:'mod', data:row});
      }
    };
    
    var deleteRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner un paramétrage d\'impression de liste.');
      if(row){
        var modalOptions = {
          closeButtonText: 'Non',
          actionButtonText: 'Oui',
          headerText: 'Confirmation',
          bodyText: 'Etes-vous sur de vouloir supprimer ce paramétrage d\'impression de liste?'
        };
        modalWindow.showModal({}, modalOptions).then(function (result){
          appFunctions.post('impressionListeManage',
            'del',
            {impression_liste_param:{id_ilp:row.id_ilp}},
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
    appFunctions.ajouterFonctionsComp('impressionListeParamList', {
      NOUVEAU:addRow,
      MODIFIER:modifyRow,
      SUPPRIMER:deleteRow
    });


    $scope.rowTitle = [
      {
        name:'lib_ilp',
        lib:'Nom',
        type:'text',
        format:''
      },{
        name:'lib_req',
        lib:'Requete',
        type:'text',
        format:'' 
      },{
        name:'icon_ctif',
        lib:'Actif',
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
    
    window.scrollTo(0,0);
  }
]);
