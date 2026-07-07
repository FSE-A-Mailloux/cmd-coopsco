/**
 * Déclaration du module userForceMdpMod
 */
angular.module('userForceMdpMod',[
  'servicesMod'
])
.controller('userForceMdpCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow',
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    $scope.user = {
      id_uti:0,
      pass_uti:''
    };
    $scope.showPassword = false;

    $scope.toggleShowPassword = function() {
      $scope.showPassword = !$scope.showPassword;
    };
    
    
    $scope.valider = function(){
      appFunctions.post('userManage',
        "forceChangePass",
        {user:$scope.user},
        function(data) {
          var modalOptions = {
            closeButtonText: '',
            actionButtonText: 'Ok',
            headerText: 'Mot de passe modifié',
            bodyText: 'Le mot de passe a bien été modifié pour le compte ['+$scope.user.compte_uti+'].'
          };
          modalWindow.showModal({}, modalOptions).then(function (result){
            appFunctions.retourComposant();
          }).catch(angular.noop);
        },
        function(response) {
          
        }
      );
    };

    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    $scope.user = params.data;
    
  }
]);
