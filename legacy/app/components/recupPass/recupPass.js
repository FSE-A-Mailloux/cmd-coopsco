/**
 * Déclaration du module userMod
 */
angular.module('recupPassMod',[
  'servicesMod'
])
.controller('recupPassCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow',
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    $scope.user = {
      mail_uti:'',
    };
    
    $scope.valider = function(){
      appFunctions.post('userManage',
        "recupPass",
        {user:$scope.user},
        function(data) {
          var modalOptions = {
            closeButtonText: '',
            actionButtonText: 'Ok',
            headerText: 'Récupération mot de passe',
            bodyText: 'Un mail contenant un lien de réinitialisation de mot de passe\na été envoyé à l\'adresse mail indiquée.\nVérifiez bien votre courrier indésirable.'
          };
          modalWindow.showModal(
            {},
            modalOptions
          ).then(function (result){
            appFunctions.loadComponent(dataStorage.appDatas.componentDefault);
          }).catch(angular.noop);
          
          
        },
        function(response) {
          
        }
      );
    };

  }
]);


