/**
 * Déclaration du module userListMod
 */
angular.module('userListMod',[
  'servicesMod'
])
.controller('userListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    //$scope.rowCollection = [];
    //$scope.displayedCollection = [];
    $scope.groupes = [];
    $scope.id_gro_select = '0';
    
    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };

    $scope.doubleclick = function(row){
      if(!dataStorage.appDatas.searsh){
        if(row){
          appFunctions.saveSelectedRow("id_uti", row.id_uti);
          appFunctions.loadComponent('userGest', {ctx:'mod', data:row});
        }
      }else{
        dataStorage.appDatas.searshCloseGetRow(row);
      }
    };
    
    $scope.selectionGroupe = function(){
      getList();
    };
    
    var getRow = function(){
      return appFunctions.getRowSelectTable($scope.displayedCollection);
    };
    
    
    var getList = function(){
      appFunctions.post('execRequest',
        'userList',
        {id_gro: $scope.id_gro_select},
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
      appFunctions.loadComponent('userGest', {ctx:'add'});
    };
    
    var modifyRow = function(){
      var row = appFunctions.getRowSelectTable($scope.rowCollection, 'Vous devez sélectionner un utilisateur');
      if(row){
        appFunctions.saveSelectedRow("id_uti", row.id_uti);
        appFunctions.loadComponent('userGest', {ctx:'mod', data:row});
      }
    };
    
    var deleteRow = function(){
      var row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner un utilisateur');
      if(row){
        var modalOptions = {
          closeButtonText: 'Non',
          actionButtonText: 'Oui',
          headerText: 'Confirmation',
          bodyText: 'Etes-vous sur de vouloir supprimer cet utilisateur?'
        };
        modalWindow.showModal({}, modalOptions).then(function (result){
          appFunctions.post('userManage',
            'del',
            {user:{id_uti:row.id_uti}},
            function(data) {
              getList();
            },
            function(response) {

            }
          );
        }).catch(angular.noop);
      }
    };
    
    var toGroupe = function(){
      var row = appFunctions.getRowSelectTable($scope.rowCollection, 'Vous devez sélectionner un utilisateur');
      if(row){
        appFunctions.saveSelectedRow("id_uti", row.id_uti);
        appFunctions.loadComponent('userGroupList', {data:row});
      }
    };
    
    var envoyerMail = function(){
      var row = appFunctions.getRowSelectTable($scope.rowCollection, 'Vous devez sélectionner un utilisateur');
      if(row){
        appFunctions.saveSelectedRow("id_uti", row.id_uti);
        appFunctions.loadComponent('mailGest', {typeDest:"LIBRE", destinataire:row.mail_uti});
      }
    };
    
    var changerMdp = function(){
      var row = appFunctions.getRowSelectTable($scope.rowCollection, 'Vous devez sélectionner un utilisateur');
      if(row){
        appFunctions.saveSelectedRow("id_uti", row.id_uti);
        appFunctions.loadComponent('userForceMdp', {ctx:'mod', data:row});
      }
    };
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('userList', {
      NOUVEAU:addRow,
      MODIFIER:modifyRow,
      SUPPRIMER:deleteRow,
      GROUPE:toGroupe,
      MAIL:envoyerMail,
      CHGMDP:changerMdp
    });

    $scope.rowTitle = [
      {
        name:'compte_uti',
        lib:'Compte',
        type:'text',
        format:'' 
      },{
        name:'nom_uti',
        lib:'Nom',
        type:'text',
        format:'' 
      },{
        name:'prenom_uti',
        lib:'Prénom',
        type:'text',
        format:'' 
      },{
        name:'dateInscription_uti',
        lib:'Date inscription',
        type:'text',
        format:'' 
      },{
        name:'dateValidation_uti',
        lib:'Date validation',
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
    
    var combos = appFunctions.getCombosComposant();
    $scope.groupes = [].concat(combos.liste_groupe);
    
  }
]);


