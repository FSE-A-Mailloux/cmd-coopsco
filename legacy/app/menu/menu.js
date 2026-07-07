/**
 * Déclaration du module sidebar
 */
angular.module('menuMod',[
  'servicesMod'
])
.controller('menuCtrl', ['$scope', 'dataStorage', 'appFunctions',
    function ($scope, dataStorage, appFunctions) {
      // variables
      
      // initialisation
      
      // fonctions
      $scope.getMenu = function() {
        return dataStorage.appDatas.menu;
      };
      
      $scope.clickMenu = function(pType, pComponent){
        if(pType === 'COM'){
          appFunctions.loadComponent(pComponent);
        }else if(pType === 'FON'){
          if(pComponent === 'RETOUR'){
            appFunctions.retourComposant();
          }else if(pComponent === 'ACCUEIL'){
            appFunctions.loadComponent(dataStorage.appDatas.componentDefault);
          }else{
            appFunctions.executerFonctionComp(pComponent);
          }
        }
      };
    }
]);