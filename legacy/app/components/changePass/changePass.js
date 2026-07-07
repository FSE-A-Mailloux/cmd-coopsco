/**
 * Déclaration du module userMod
 */
angular.module('changePassMod',[
  'servicesMod'
])
.controller('changePassCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow',
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    $scope.user = {
      pass_uti:'',
      idCode:''
    };
    $scope.showPassword = false;

    $scope.toggleShowPassword = function() {
      $scope.showPassword = !$scope.showPassword;
    };
    
    
    $scope.valider = function(){
      appFunctions.post('userManage',
        "changePass",
        {user:$scope.user},
        function(data) {
          var modalOptions = {
            closeButtonText: '',
            actionButtonText: 'Ok',
            headerText: 'Mot de passe modifié',
            bodyText: 'Votre mot de passe a bien été modifié pour le compte ['+data.response.compte_uti+'].<br>Vous pouvez vous authentifier de nouveau.'
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


