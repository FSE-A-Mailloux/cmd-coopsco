/**
 * Déclaration du module autorisationListMod
 */
angular.module('autorisationListMod',[
  'servicesMod'
])
.controller('autorisationListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
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
        'autorisationList',
        {id_gro:params.data.id_gro},
        function(data) {
          $scope.rowCollection = data.response.result;
          $scope.displayedCollection = [].concat($scope.rowCollection);
        },
        function(response) {

        }
      );
    };

    var autoriser = function(){
      var collection = $scope.displayedCollection;
      var liste_com = "'-'";
      var liste_fon = "'-'";
      var liste_etat = "'-'";
      var ligne_select = false;
      for(var i = 0; i < collection.length; i++){
        if(collection[i].isSelected){
          ligne_select = true;
          if(collection[i].type_aut === 'COM'){
            liste_com += ",'" + collection[i].code_aut + "'";
          }else if(collection[i].type_aut === 'FON'){
            liste_fon += ",'" + collection[i].code_aut + "'";
          }else{
            liste_etat += ",'" + collection[i].code_aut + "'";
          }
        }
      }
      if(ligne_select){
        appFunctions.post('autorisationManage',
          'add',
          {autorisation:{id_gro:params.data.id_gro, liste_fon: liste_fon, liste_com: liste_com, liste_etat: liste_etat}},
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
      var liste_etat = "'-'";
      var ligne_select = false;
      for(var i = 0; i < collection.length; i++){
        if(collection[i].isSelected){
          ligne_select = true;
          if(collection[i].type_aut === 'COM'){
            liste_com += ",'" + collection[i].code_aut + "'";
          }else if(collection[i].type_aut === 'FON'){
            liste_fon += ",'" + collection[i].code_aut + "'";
          }else{
            liste_etat += ",'" + collection[i].code_aut + "'";
          }
        }
      }
      if(ligne_select){
        appFunctions.post('autorisationManage',
          'del',
          {autorisation:{id_gro:params.data.id_gro, liste_fon: liste_fon, liste_com: liste_com, liste_etat: liste_etat}},
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
    $scope.libGroupe = params.data.nom_gro;
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('autorisationList', {
      autoriser:autoriser,
      interdire:interdire
    });

    $scope.rowTitle = [
      {
        name:'icon_aut',
        lib:'',
        type:'icon',
        format:'' 
      },{
        name:'lib_type_aut',
        lib:'type',
        type:'text',
        format:'' 
      },{
        name:'code_aut',
        lib:'Code',
        type:'text',
        format:'' 
      },{
        name:'lib_aut',
        lib:'Description',
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


