/**
 * Déclaration du module fseArticleListMod
 */
angular.module('fseArticleListMod',[
  'servicesMod'
])
.controller('fseArticleListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
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
        appFunctions.loadComponent('fseArticleGest', {ctx:'mod', data:row});
      }
    };
    
    var getList = function(){
      appFunctions.post('execRequest',
        'fseArticleList',
        {ctx:'tous'},
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
      appFunctions.loadComponent('fseArticleGest', {ctx:'add'});
    };
    
    var modifyRow = function(){
      var row = appFunctions.getRowSelectTable($scope.rowCollection, 'Vous devez sélectionner un article');
      if(row){
        appFunctions.saveSelectedRow("id_art", row.id_art);
        appFunctions.loadComponent('fseArticleGest', {ctx:'mod', data:row});
      }
    };
    
    var deleteRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner un article');
      if(row){
        var modalOptions = {
          closeButtonText: 'Non',
          actionButtonText: 'Oui',
          headerText: 'Confirmation',
          bodyText: 'Etes-vous sur de vouloir supprimer cet article?'
        };
        modalWindow.showModal({}, modalOptions).then(function (result){
          appFunctions.post('fseArticleManage',
            'del',
            {fse_article:{id_art:row.id_art}},
            function(data) {
              getList();
            },
            function(response) {

            }
          );
        }).catch(angular.noop);
      }
    };
    
    var prix = function(){
      var row = appFunctions.getRowSelectTable($scope.rowCollection, 'Vous devez sélectionner un article');
      if(row){
        appFunctions.saveSelectedRow("id_art", row.id_art);
        appFunctions.loadComponent('fseArticlePrixGest', {ctx:'mod', data:row});
      }
    };
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('fseArticleList', {
      NOUVEAU:addRow,
      MODIFIER:modifyRow,
      SUPPRIMER:deleteRow,
      PRIX:prix
    });

    $scope.rowTitle = [
      {
        name:'ordre_art',
        lib:'',
        type:'text',
        format:'' 
      },{
        name:'design_fou',
        lib:'Fournisseur',
        type:'text',
        format:'' 
      },{
        name:'code_art',
        lib:'Code',
        type:'text',
        format:'' 
      },{
        name:'icon_type_art',
        lib:'',
        type:'icon',
        format:'' 
      },{
        name:'lib_art',
        lib:'Description',
        type:'text',
        format:'' 
      },{
        name:'marque_art',
        lib:'Marque',
        type:'text',
        format:'' 
      },{
        name:'prix_pafe_str',
        lib:'Prix famille en cours',
        type:'text',
        format:'' 
      },{
        name:'vue_cmdfam_art',
        lib:'Dans commande',
        type:'text',
        format:'' 
      },{
        name:'vue_stock_art',
        lib:'Dans stock',
        type:'text',
        format:'' 
      }
    ];

    $scope.refreshList = function(){
      getList();
    };

    $scope.refreshList = function(){
      getList();
    };
    
    getList();
    
  }
]);


