/**
 * Déclaration du module desaboMod
 */
angular.module('desaboMod',[
  'servicesMod'
])
.controller('desaboCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow',
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    $scope.user = {
      mail_uti:'',
      idCode:''
    };
    
    $scope.valider = function(){
      appFunctions.post('userManage',
        "desabo",
        {user:$scope.user},
        function(data) {
          data.response.compte_uti
          var modalOptions = {
            closeButtonText: '',
            actionButtonText: 'Ok',
            headerText: 'Désabonnement confirmé',
            bodyText: 'Votre désabonnement à la liste de difusion est maintenant effective.'
          };
          modalWindow.showModal({}, modalOptions).then(function (result){
            appFunctions.loadComponent(dataStorage.appDatas.componentDefault);
          }).catch(angular.noop);
          
          
        },
        function(response) {
          
        }
      );
    };

    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    $scope.user.idCode = params.idCode;

  }
]);


