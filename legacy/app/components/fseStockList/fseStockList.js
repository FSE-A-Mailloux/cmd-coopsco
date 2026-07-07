/**
 * Déclaration du module fseStockListMod
 */
angular.module('fseStockListMod',[
  'servicesMod'
])
.controller('fseStockListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
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
        appFunctions.saveSelectedRow("id_art", row.id_art);
        appFunctions.loadComponent('fseStockGest', {ctx:'mod', data:row});
      }
    };
    
    var getList = function(){
      appFunctions.post('execRequest',
        'getFseStock',
        {id_art:-1},
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
    
    var modifyRow = function(){
      var row = appFunctions.getRowSelectTable($scope.rowCollection, 'Vous devez sélectionner un article');
      if(row){
        appFunctions.saveSelectedRow("id_art", row.id_art);
        appFunctions.loadComponent('fseStockGest', {ctx:'mod', data:row});
      }
    };

    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('fseStockList', {
      MODIFIER:modifyRow
    });

    $scope.rowTitle = [
      {
        name:'code_art',
        lib:'Code',
        type:'text',
        format:'' 
      },{
        name:'lib_art',
        lib:'Nom',
        type:'text',
        format:'' 
      },{
        name:'nb_sto',
        lib:'Nbr unité',
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


