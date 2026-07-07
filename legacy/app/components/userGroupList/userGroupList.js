/**
 * Déclaration du module autorisationListMod
 */
angular.module('userGroupListMod',[
  'servicesMod'
])
.controller('userGroupListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    $scope.rowCollection = [];
    $scope.displayedCollection = [];
    
    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
    var getList = function(){
      appFunctions.post('execRequest',
        'userGroupList',
        {id_uti:params.data.id_uti},
        function(data) {
          $scope.rowCollection = data.response.result;
          $scope.displayedCollection = [].concat($scope.rowCollection);
        },
        function(response) {

        }
      );
    };
/*
    var autoriser = function(){
      var collection = $scope.displayedCollection;
      var liste_com = "'-'";
      var liste_fon = "'-'";
      var ligne_select = false;
      for(var i = 0; i < collection.length; i++){
        if(collection[i].isSelected){
          ligne_select = true;
          if(collection[i].type_aut === 'COM'){
            liste_com += ",'" + collection[i].code_aut + "'";
          }else{
            liste_fon += ",'" + collection[i].code_aut + "'";
          }
        }
      }
      if(ligne_select){
        appFunctions.post('autorisationManage',
          'add',
          {autorisation:{id_gro:params.data.id_gro, liste_fon: liste_fon, liste_com: liste_com}},
          function(data) {
            getList();
          },
          function(response) {

          }
        );
      }
      
    };
    
    var interdire = function(){
      var collection = $scope.displayedCollection;
      var liste_com = "'-'";
      var liste_fon = "'-'";
      var ligne_select = false;
      for(var i = 0; i < collection.length; i++){
        if(collection[i].isSelected){
          ligne_select = true;
          if(collection[i].type_aut === 'COM'){
            liste_com += ",'" + collection[i].code_aut + "'";
          }else{
            liste_fon += ",'" + collection[i].code_aut + "'";
          }
        }
      }
      if(ligne_select){
        appFunctions.post('autorisationManage',
          'del',
          {autorisation:{id_gro:params.data.id_gro, liste_fon: liste_fon, liste_com: liste_com}},
          function(data) {
            getList();
          },
          function(response) {

          }
        );
      }
      
    };
*/

    var entrer = function(){
      var collection = $scope.displayedCollection;
      var liste_gro = "-1";
      var ligne_select = false;
      for(var i = 0; i < collection.length; i++){
        if(collection[i].isSelected){
          ligne_select = true;
          liste_gro += "," + collection[i].id_gro;
        }
      }
      if(ligne_select){
        appFunctions.post('userGroupManage',
          'add',
          {gro_uti:{id_uti:params.data.id_uti, liste_gro: liste_gro}},
          function(data) {
            getList();
          },
          function(response) {

          }
        );
      }
    };

    var sortir = function(){
      var collection = $scope.displayedCollection;
      var liste_gro = "-1";
      var ligne_select = false;
      for(var i = 0; i < collection.length; i++){
        if(collection[i].isSelected){
          ligne_select = true;
          liste_gro += "," + collection[i].id_gro;
        }
      }
      if(ligne_select){
        appFunctions.post('userGroupManage',
          'del',
          {gro_uti:{id_uti:params.data.id_uti, liste_gro: liste_gro}},
          function(data) {
            getList();
          },
          function(response) {

          }
        );
      }
    };
    
    var defaut = function(){
      var row = appFunctions.getRowSelectTable($scope.rowCollection, 'Vous devez sélectionner un groupe');
      if(row){
        appFunctions.post('userGroupManage',
          'default',
          {gro_uti:row},
          function(data) {
            getList();
          },
          function(response) {

          }
        );
      }
    };
    // Code démarrage

    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    $scope.libUti = params.data.compte_uti;
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('userGroupList', {
      ENTRER:entrer,
      SORTIR:sortir,
      DEFAUT:defaut
    });

    $scope.rowTitle = [
      {
        name:'icon_user',
        lib:'',
        type:'icon',
        format:'' 
      },{
        name:'nom_gro',
        lib:'Groupe',
        type:'text',
        format:'' 
      },{
        name:'cd_com',
        lib:'Composant initial',
        type:'text',
        format:'' 
      },{
        name:'icon_def',
        lib:'',
        type:'icon',
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


