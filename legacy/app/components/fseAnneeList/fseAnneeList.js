/**
 * Déclaration du module userListMod
 */
angular.module('fseAnneeListMod',[
  'servicesMod'
])
.controller('fseAnneeListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
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
        appFunctions.saveSelectedRow("id_ann", row.id_ann);
        appFunctions.loadComponent('fseAnneeGest', {ctx:'mod', data:row});
      }
    };
    
    var getList = function(){
      appFunctions.post('execRequest',
        'fseAnneeList',
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
      appFunctions.loadComponent('fseAnneeGest', {ctx:'add'});
    };
    
    var modifyRow = function(){
      var row = appFunctions.getRowSelectTable($scope.rowCollection, 'Vous devez sélectionner une période');
      if(row){
        appFunctions.saveSelectedRow("id_ann", row.id_ann);
        appFunctions.loadComponent('fseAnneeGest', {ctx:'mod', data:row});
      }
    };
    
    var deleteRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une période');
      if(row){
        var modalOptions = {
          closeButtonText: 'Non',
          actionButtonText: 'Oui',
          headerText: 'Confirmation',
          bodyText: 'Etes-vous sur de vouloir supprimer cette période?'
        };
        modalWindow.showModal({}, modalOptions).then(function (result){
          appFunctions.post('fseAnneeManage',
            'del',
            {fse_annee:{id_ann:row.id_ann}},
            function(data) {
              getList();
            },
            function(response) {

            }
          );
        }).catch(angular.noop);
      }
    };
    
    var enCours = function(){
      var row = appFunctions.getRowSelectTable($scope.rowCollection, 'Vous devez sélectionner une période');
      if(row){
        appFunctions.post('fseAnneeManage',
          'encours',
          {fse_annee:{id_ann:row.id_ann}},
          function(data) {
            getList();
          },
          function(response) {

          }
        );
      }
    };
    
    
    
    var copierPrix = function(){
      var row = appFunctions.getRowSelectTable($scope.rowCollection, 'Vous devez sélectionner une période');
      if(row){
        if(row.verrou_ann === 1){
          var modalOptions = {
            closeButtonText: '',
            actionButtonText: 'Ok',
            headerText: 'Information',
            bodyText: 'Cette période est vérouillée'
          };
          modalWindow.showModal({}, modalOptions).catch(angular.noop);
        }else{
          var modalOptions = {
            closeButtonText: 'Non',
            actionButtonText: 'Oui',
            headerText: 'Confirmation',
            bodyText: 'Voulez-vous écraser les prix de la périodes sélectionnées?'
          };
          modalWindow.showModal({}, modalOptions).then(function (result){
            appFunctions.post('fseArticlePrixManage',
              'copie',
              {fse_annee:{id_ann:row.id_ann}},
              function(data) {
                var modalOptions = {
                  closeButtonText: '',
                  actionButtonText: 'Ok',
                  headerText: 'Information',
                  bodyText: 'Les prix ont été copiés depuis la période en cours'
                };
                modalWindow.showModal({}, modalOptions).catch(angular.noop);

              },
              function(response) {

              }
            );
          }).catch(angular.noop);
          
        }
      }
    };
    
    var ouvFerm = function(){
      var row = appFunctions.getRowSelectTable($scope.rowCollection, 'Vous devez sélectionner une période');
      if(row){
        appFunctions.post('fseAnneeManage',
          'ouvferm',
          {fse_annee:{id_ann:row.id_ann}},
          function(data) {
            getList();
          },
          function(response) {

          }
        );
      }
    };
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('fseAnneeList', {
      NOUVEAU:addRow,
      MODIFIER:modifyRow,
      SUPPRIMER:deleteRow,
      ENCOURS:enCours,
      COPIERPRIX:copierPrix,
      OUVFERM:ouvFerm
    });

    $scope.rowTitle = [
      {
        name:'icon_ouverte_ann',
        lib:'',
        type:'icon',
        format:'' 
      },{
        name:'icon_ann',
        lib:'',
        type:'icon',
        format:'' 
      },{
        name:'lib_ann',
        lib:'Libellé',
        type:'text',
        format:'' 
      },{
        name:'prefix_ann',
        lib:'Préfixe n° cmd',
        type:'text',
        format:'' 
      },{
        name:'dtbutoir_ann',
        lib:'Date butoir',
        type:'text',
        format:'' 
      },{
        name:'icon_verrou_ann',
        lib:'',
        type:'icon',
        format:'' 
      }
    ];

    $scope.refreshList = function(){
      getList();
    };
    
    getList();
    
  }
]);


