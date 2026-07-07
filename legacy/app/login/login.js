/**
 * Déclaration du module sidebar
 */
angular.module('loginMod',[
  'servicesMod'
])
.controller('loginCtrl', ['$scope', 'dataStorage', 'appFunctions', 
    function ($scope, dataStorage, appFunctions) {
      // variables
      $scope.compte = {
        login: '',
        mdp:''
      };
      
      // initialisation
      
      
      // fonctions
      $scope.authentifier = function(){
        appFunctions.post('login', '', {compte_uti:$scope.compte.login, pass_uti:$scope.compte.mdp}, function(data) {
          // si l'utilisateur n'est pas validé
          if(data.response.dateValidation_uti === null){
            appFunctions.loadComponent('validUser', {id_uti: data.response.id_uti, compte_uti:$scope.compte.login});
          }else{
            appFunctions.connexion(data.response);
          }
          $scope.compte = {
            login: '',
            mdp:''
          };
        });
      };
      
      $scope.deconnecter = function(){
        appFunctions.deconnexion();
      };
      
      $scope.creerCompte = function(){
        // appeler le composant user avec le token de l'utilisateur en mode modification
        appFunctions.loadComponent('userGest', {ctx:'new'});
      };
      
      $scope.modifierCompte = function(){
        appFunctions.loadComponent('userGest', {ctx:'cha', data:{id_uti:dataStorage.appDatas.id_uti}});
      };
      
      $scope.recupMdp = function(){
        appFunctions.loadComponent('recupPass');
      };
      
      $scope.changerGroupe = function(){
        appFunctions.loadComponent(dataStorage.appDatas.componentDefault);
      };
    }
]);