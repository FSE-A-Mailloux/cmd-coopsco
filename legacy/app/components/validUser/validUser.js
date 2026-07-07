/**
 * Déclaration du module validUserMod
 */
angular.module('validUserMod',[
  'servicesMod'
])
.controller('validUserCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow',
  function ($scope, dataStorage, appFunctions, modalWindow) {


    // variables
    $scope.codeValidation = '';

    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    
    // si le contexte n'est pas définit
    if(!params.id_uti){
      appFunctions.loadComponent('default');
    }
    var id_uti = params.id_uti;
    var compte_uti = params.compte_uti;

    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
    $scope.validerCode = function(){
      appFunctions.post('login',
        'validUser',
        {
          compte_uti: compte_uti,
          codeValidation_uti: $scope.codeValidation
        },
        function(data) {
          var modalOptions = {
            closeButtonText: '',
            actionButtonText: 'Ok',
            headerText: 'Activation terminée',
            bodyText: 'L\'activation de votre compte est maintenant terminée.'
          };
          modalWindow.showModal({}, modalOptions).catch(angular.noop);

          appFunctions.connexion(data.response);
        }
      );
    };
    
    $scope.renvoyerCode = function(){
      appFunctions.post('userManage',
        'resendCode',
        {
          user:{
            id_uti: id_uti,
            compte_uti: compte_uti
          }
        },
        function(data) {
          var modalOptions = {
            closeButtonText: '',
            actionButtonText: 'Ok',
            headerText: 'Envoi code',
            bodyText: 'Un nouveau code d\'activation vous sera envoyé à votre adresse mail.'
          };
          modalWindow.showModal({}, modalOptions).catch(angular.noop);
        }
      );
    };
  }
]);

