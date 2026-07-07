/**
 * Déclaration du module fseFournisseurListMod
 */
angular.module('fseFournisseurListMod',[
  'servicesMod'
])
.controller('fseFournisseurListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
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
        appFunctions.saveSelectedRow("id_fou", row.id_fou);
        appFunctions.loadComponent('fseFournisseurGest', {ctx:'mod', data:row});
      }
    };
    
    var getList = function(){
      appFunctions.post('execRequest',
        'listFseFournisseur',
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
      appFunctions.loadComponent('fseFournisseurGest', {ctx:'add'});
    };
    
    var modifyRow = function(){
      var row = appFunctions.getRowSelectTable($scope.rowCollection, 'Vous devez sélectionner un fournisseur');
      if(row){
        appFunctions.saveSelectedRow("id_fou", row.id_fou);
        appFunctions.loadComponent('fseFournisseurGest', {ctx:'mod', data:row});
      }
    };
    
    var deleteRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner un fournisseur');
      if(row){
        var modalOptions = {
          closeButtonText: 'Non',
          actionButtonText: 'Oui',
          headerText: 'Confirmation',
          bodyText: 'Etes-vous sur de vouloir supprimer ce fournisseur?'
        };
        modalWindow.showModal({}, modalOptions).then(function (result){
          appFunctions.post('fseFournisseurManage',
            'del',
            {fse_fournisseur:{id_fou:row.id_fou}},
            function(data) {
              getList();
            },
            function(response) {

            }
          );
        }).catch(angular.noop);
      }
    };
    
    var mailer = function(){
      var row = appFunctions.getRowSelectTable($scope.rowCollection, 'Vous devez sélectionner un fournisseur');
      if(row){
        appFunctions.saveSelectedRow("id_fou", row.id_fou);
        appFunctions.loadComponent('mailGest', {typeDest:"LIBRE", destinataire:row.mail_fou});
      }
    };
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('fseFournisseurList', {
      NOUVEAU:addRow,
      MODIFIER:modifyRow,
      SUPPRIMER:deleteRow,
      MAIL:mailer
    });

    $scope.rowTitle = [
      {
        name:'design_fou',
        lib:'Désignation',
        type:'text',
        format:'' 
      },{
        name:'tel_fou',
        lib:'Téléphone',
        type:'text',
        format:'' 
      },{
        name:'mail_fou',
        lib:'Email',
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


