/**
 * Déclaration du module userMod
 */
angular.module('changeMyPassMod',[
  'servicesMod'
])
.controller('changeMyPassCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow',
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    $scope.user = {
      passOld_uti:'',
      pass_uti:''
    };
    $scope.showPassword = false;
    $scope.showOldPassword = false;

    $scope.toggleShowPassword = function() {
      $scope.showPassword = !$scope.showPassword;
    };

    $scope.toggleShowOldPassword = function() {
      $scope.showOldPassword = !$scope.showOldPassword;
    };
    
    
    $scope.valider = function(){
      appFunctions.post('userManage',
        "changeMyPass",
        {user:$scope.user},
        function(data) {
          data.response.compte_uti
          var modalOptions = {
            closeButtonText: '',
            actionButtonText: 'Ok',
            headerText: 'Mot de passe modifié',
            bodyText: 'Votre mot de passe a bien été modifié.'
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
    //var params = appFunctions.getParametresComposant();
    //$scope.user.idCode = params.idCode;

  }
]);


