/**
 * Déclaration du module userListMod
 */
angular.module('etatparamListMod',[
  'servicesMod'
])
.controller('etatparamListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
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
        appFunctions.saveSelectedRow("cd_epm", row.cd_epm);
        appFunctions.loadComponent('etatparamGest', {ctx:'mod', data:row});
      }
    };
    
    var getList = function(){
      appFunctions.post('execRequest',
        'etatparamList',
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

    // Récupération paramètres
    //var params = appFunctions.getParametresComposant();
    
    var addRow = function(){
      appFunctions.loadComponent('etatparamGest', {ctx:'add'});
    };
    
    var modifyRow = function(){
      var row = appFunctions.getRowSelectTable($scope.rowCollection, 'Vous devez sélectionner un paramétrage');
      if(row){
        appFunctions.saveSelectedRow("cd_epm", row.cd_epm);
        appFunctions.loadComponent('etatparamGest', {ctx:'mod', data:row});
      }
    };
    
    var deleteRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner un paramétrage');
      if(row){
        var modalOptions = {
          closeButtonText: 'Non',
          actionButtonText: 'Oui',
          headerText: 'Confirmation',
          bodyText: 'Etes-vous sur de vouloir supprimer ce paramétrage?'
        };
        modalWindow.showModal({}, modalOptions).then(function (result){
          appFunctions.post('etatparamManage',
            'del',
            {etatparam:{cd_epm:row.cd_epm}},
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
    appFunctions.ajouterFonctionsComp('etatparamList', {
      NOUVEAU:addRow,
      MODIFIER:modifyRow,
      SUPPRIMER:deleteRow
    });

    $scope.rowTitle = [
      {
        name:'cd_epm',
        lib:'Code',
        type:'text',
        format:'' 
      },{
        name:'lib_epm',
        lib:'Libellé',
        type:'text',
        format:'' 
      },{
        name:'code_req',
        lib:'Requête',
        type:'text',
        format:'' 
      },{
        name:'code_mhl',
        lib:'Modèle pdf',
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


