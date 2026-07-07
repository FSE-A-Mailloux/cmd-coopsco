/**
 * Déclaration du module fseListCmdEnCoursMod
 */
angular.module('fseListCmdUtiMod',[
  'servicesMod'
])
.controller('fseListCmdUtiCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    //$scope.rowCollection = [];
    //$scope.displayedCollection = [];
    
    // fonctions

    
    $scope.doubleclick = function(row){
      if(row){
        appFunctions.saveSelectedRow("id_cmd", row.id_cmd);
        appFunctions.loadComponent('fseCmdDetail', {ctx:'modUti', data:row});
      }
    };
   
    var getList = function(){
      appFunctions.post('execRequest',
        'fseCmdFamilleList',
        {id_uti:dataStorage.appDatas.id_uti},
        function(data) {
          $scope.selectedRow = appFunctions.loadSelectedRow();
          $scope.rowCollection = data.response.result;
          $scope.displayedCollection = [].concat($scope.rowCollection);
        },
        function(response) {

        }
      );
    };

    var detail = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une commande');
      if(row){
        appFunctions.saveSelectedRow("id_cmd", row.id_cmd);
        appFunctions.loadComponent('fseCmdDetail', {ctx:'modUti', data:row});
      }
    };
    
    var getPdf = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une commande');
      if(row){
        appFunctions.post('fseCmdManage',
          'pdfUti',
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
    
    // fonction de création de commande avec compte Gestionnaire FSE
    var creerCmd = function(){
      appFunctions.loadComponent('fseCmdInit', {ctx:'new'});
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
            bodyText: 'Etes-vous vraiment sur??'
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
    
    // Fonction permettant d'annuler une commande
    var paiementCbCmd = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner une commande');
      if(row){
        var message = "";
        // Vérifier si la commande est soldée
        if(Number(row.solde_cmd_num) === 0 ) {
          message = 'La commande est déjà soldée';
        
        // Si la commande est validée
        }else if(Number(row.cmd_valid) === 1){
          message = 'La commande est déjà soldée';
          
        // Si la commande est annulée
        }else if(Number(row.annul_cmd) === 1){
          message = 'La commande est annulée';
          
        }
        
        if(message !== ""){
          var modalOptions = {
            closeButtonText: 'OK',
            actionButtonText: '',
            headerText: 'Information',
            bodyText: message
          };
          modalWindow.showModal({}, modalOptions).then(function (result){}).catch(angular.noop);
        }else{
          appFunctions.loadComponent('fseCmdRgltCb', {ctx:'cb', data:row});
         
        }
      }
    };
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('fseListCmdUti', {
      DETAIL:detail,
      PDF:getPdf,
      CREERCMD:creerCmd,
      ANNULER_CMD:annulerCmd,
      PAIEMENTCBCMD:paiementCbCmd
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


