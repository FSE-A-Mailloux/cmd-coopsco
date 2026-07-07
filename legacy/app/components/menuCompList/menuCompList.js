/**
 * Déclaration du module menuCompListMod
 */
angular.module('menuCompListMod',[
  'servicesMod'
])
.controller('menuCompListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
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
        appFunctions.saveSelectedRow("id_mco", row.id_mco);
        appFunctions.loadComponent('menuGest', {ctx:'mod', data:row});
      }
    };
    
    var getList = function(){
      appFunctions.post('execRequest',
        'menuCompList',
        {cd_com:params.data.cd_com},
        function(data) {
          $scope.selectedRow = appFunctions.loadSelectedRow();
          $scope.rowCollection = data.response.result;
          // boucle crade pour changer le type d'une données
          for(var i = 0; i < $scope.rowCollection.length; i++) {
            $scope.rowCollection[i].position_mco = Number($scope.rowCollection[i].position_mco);
          }
          
          $scope.displayedCollection = [].concat($scope.rowCollection);
        },
        function(response) {

        }
      );
    };

    var addRow = function(){
      appFunctions.loadComponent('menuGest', {ctx:'add', cd_com:params.data.cd_com, lib_com:params.data.lib_com});
    };
    
    var modifyRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une option de menu');
      if(row){
        appFunctions.saveSelectedRow("id_mco", row.id_mco);
        appFunctions.loadComponent('menuGest', {ctx:'mod', data:row, cd_com:params.data.cd_com, lib_com:params.data.lib_com});
      }
    };
    
    var deleteRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une option de menu');
      if(row){
        var modalOptions = {
          closeButtonText: 'Non',
          actionButtonText: 'Oui',
          headerText: 'Confirmation',
          bodyText: 'Etes-vous sur de vouloir supprimer cette option de menu?'
        };
        modalWindow.showModal({}, modalOptions).then(function (result){
          appFunctions.post('menuManage',
            'del',
            {menu:{id_mco:row.id_mco, cd_com:params.data.cd_com}},
            function(data) {
              getList();
            },
            function(response) {

            }
          );
        }).catch(angular.noop);
      }
    };
    
    // Code démarrage

    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    $scope.titreComp = '['+ params.data.cd_com +'] ' + params.data.lib_com
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('menuCompList', {
      NOUVEAU:addRow,
      MODIFIER:modifyRow,
      SUPPRIMER:deleteRow
    });

    $scope.rowTitle = [
      {
        name:'nom_mco',
        lib:'Nom',
        type:'text',
        format:'' 
      },{
        name:'glyphicon_mco',
        lib:'Icone',
        type:'icon',
        format:'' 
      },{
        name:'position_mco',
        lib:'Position',
        type:'text',
        format:'' 
      },{
        name:'typeOption_mco',
        lib:'Type',
        type:'text',
        format:'' 
      },{
        name:'cdComDest_mco',
        lib:'composant',
        type:'text',
        format:'' 
      }
    ];
    
    var params = appFunctions.getParametresComposant();

    $scope.refreshList = function(){
      getList();
    };

    getList();
    
  }
]);


