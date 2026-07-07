/**
 * Déclaration du module fseListCmdEnCoursMod
 */
angular.module('fseListCmdEnCoursMod',[
  'servicesMod'
])
.controller('fseListCmdEnCoursCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
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
        appFunctions.saveSelectedRow("id_cmd", row.id_cmd);
        appFunctions.loadComponent('fseCmdDetail', {ctx:'mod', data:row});
      }
    };
   
    var getList = function(){
      appFunctions.post('execRequest',
        'fseCmdEnCoursList',
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

    var regler = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une commande');
      if(row){
        appFunctions.saveSelectedRow("id_cmd", row.id_cmd);
        appFunctions.loadComponent('fseCmdRegler', {ctx:'mod', data:row});
      }
    };
    
    var detail = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une commande');
      if(row){
        appFunctions.saveSelectedRow("id_cmd", row.id_cmd);
        appFunctions.loadComponent('fseCmdDetail', {ctx:'mod', data:row});
      }
    };
    
    var mail = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une commande');
      if(row){
        appFunctions.saveSelectedRow("id_cmd", row.id_cmd);
        appFunctions.loadComponent('mailGest', {typeDest:"LIBRE", destinataire:row.mail_cmd});
      }
    };
    
    var getPdf = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une commande');
      if(row){
        appFunctions.post('fseCmdManage',
          'pdf',
          {fse_commande:{id_cmd:row.id_cmd}},
          function(data) {
            if(data.response.pdfBase64){
              appFunctions.saveBase64(data.response.pdfBase64, data.response.nomPdf);
            }
            data.response.pdfBase64 = null;
          },
          function(response) {

          }
        );
      }
    };
    
    var getFacture = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une commande');
      if(row){
        appFunctions.post('fseCmdManage',
          'facture',
          {fse_commande:{id_cmd:row.id_cmd}},
          function(data) {
            if(data.response.pdfBase64){
              appFunctions.saveBase64(data.response.pdfBase64, data.response.nomPdf);
            }
            data.response.pdfBase64 = null;
          },
          function(response) {

          }
        );
      }
    };
    
    var retirerCotisationFse = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une commande');
      if(row){
        var modalOptions = {
          closeButtonText: 'Non',
          actionButtonText: 'Oui',
          headerText: 'Confirmation',
          bodyText: 'Etes-vous sur de vouloir supprimer la cotisation FSE?'
        };
        modalWindow.showModal({}, modalOptions).then(function (result){
          var modalOptions = {
            closeButtonText: 'Non',
            actionButtonText: 'Oui',
            headerText: 'Confirmation',
            bodyText: 'Opération définitive, êtes-vous vraiment sur??'
          };
          modalWindow.showModal({}, modalOptions).then(function (result){
            appFunctions.post('fseCmdManage',
              'supprcotis',
              {fse_commande:{id_cmd:row.id_cmd}},
              function(data) {
                getList();
              },
              function(response) {

              }
            );
          }).catch(angular.noop);
        }).catch(angular.noop);
      }
    };
    
    // fonction de création de commande avec compte Gestionnaire FSE
    var creerCmd = function(){
      appFunctions.loadComponent('fseCmdInit', {ctx:'add'});
    };
    
    // Fonction permettant d'annuler une commande
    var annulerCmd = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une commande');
      if(row){
        var modalOptions = {
          closeButtonText: 'Non',
          actionButtonText: 'Oui',
          headerText: 'Confirmation',
          bodyText: 'Etes-vous sur de vouloir annuler cette commande?'
        };
        modalWindow.showModal({}, modalOptions).then(function (result){
          var modalOptions = {
            closeButtonText: 'Non',
            actionButtonText: 'Oui',
            headerText: 'Confirmation',
            bodyText: 'Opération définitive, êtes-vous vraiment sur??'
          };
          modalWindow.showModal({}, modalOptions).then(function (result){
            appFunctions.post('fseCmdManage',
              'annulcmd',
              {fse_commande:{id_cmd:row.id_cmd}},
              function(data) {
                getList();
              },
              function(response) {

              }
            );
          }).catch(angular.noop);
        }).catch(angular.noop);
      }
    };
    
    var histoCmd = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une commande');
      if(row){
        appFunctions.saveSelectedRow("id_cmd", row.id_cmd);
        appFunctions.loadComponent('fseCmdHistoList', {ctx:'mod', data:row});
      }
    };
    
    var relance = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une commande');
      if(row){
        if(row.valid_cmd == 1){
          var modalOptions = {
            closeButtonText: '',
            actionButtonText: 'Ok',
            headerText: 'Information',
            bodyText: "Il n'est pas possible de relancer une commance validée"
          };
          modalWindow.showModal({}, modalOptions).catch(angular.noop);
        }else{
          var modalOptions = {
            closeButtonText: 'Non',
            actionButtonText: 'Oui',
            headerText: 'Confirmation',
            bodyText: 'Etes-vous sur de vouloir relancer par mail la demande du règlement?'
          };
          modalWindow.showModal({}, modalOptions).then(function (result){
            appFunctions.post('fseCmdManage',
              'relance',
              {fse_commande:{id_cmd:row.id_cmd}},
              function(data) {
                var modalOptions = {
                  closeButtonText: '',
                  actionButtonText: 'Ok',
                  headerText: 'Information',
                  bodyText: "Un mail de relance vient d'être envoyé"
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
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('fseListCmdEnCours', {
      REGLER:regler,
      DETAIL:detail,
      MAIL:mail,
      PDF:getPdf,
      FACTURE:getFacture,
      CREERCMD:creerCmd,
      ANNULER_CMD:annulerCmd,
      HISTO:histoCmd,
      RELANCE:relance,
      SUPPRCOTIS:retirerCotisationFse
    });

    $scope.rowTitle = [
      {
        name:'icon_valid',
        lib:'',
        type:'icon',
        format:'' 
      },{
        name:'num_cmd',
        lib:'N° commande',
        type:'text',
        format:'' 
      },{
        name:'dtcre_cmd',
        lib:'Date création',
        type:'text',
        format:'' 
      },{
        name:'nomprenom_cmd',
        lib:'Nom commanditaire',
        type:'text',
        format:'' 
      },{
        name:'nbrenf_cmd',
        lib:'Nbre d\'enfants',
        type:'text',
        format:'' 
      },{
        name:'total_cmd',
        lib:'Montant',
        type:'text',
        format:'' 
      },{
        name:'solde_cmd',
        lib:'Solde',
        type:'text',
        format:'' 
      },{
        name:'dtvalid_cmd',
        lib:'Date validation',
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


