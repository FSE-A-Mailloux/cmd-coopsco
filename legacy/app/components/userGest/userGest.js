/**
 * Déclaration du module userMod
 */
angular.module('userMod',[
  'servicesMod'
])
.controller('userCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow',
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    $scope.user = {
      id_uti: -1,
      mail_uti:'',
      compte_uti :'',
      pass_uti:'',
      nom_uti:'',
      prenom_uti:'',
      comGroupe_uti:1,
      recaptchaResponse: ''
    };
    $scope.showPassword = false;
    $scope.captchaPublicKey = dataStorage.params.captchaPublicKey;

    $scope.toggleShowPassword = function() {
      $scope.showPassword = !$scope.showPassword;
    };
    
    // fonctions
    //$scope.getDatas = function() {
    //  return dataStorage;
    //};
     
    $scope.captchaActif = function(){
      return (dataStorage.params.captchaPublicKey !== "" && dataStorage.appDatas.token === "");
    };

    $scope.valider = function(){
      var message = "";

      // dans le cas d'un contexte de modification, il faut alerter d'un changement de mail et de mot de passe
      if(params.ctx === 'cha' || params.ctx === 'mod'){
        if($scope.user.pass_uti_save !== $scope.user.pass_uti){
          message = "- Mot de passe";
        }
        if($scope.user.mail_uti_save !== $scope.user.mail_uti){
          if(message !== ""){
            message += "<br>";
          }
          message = "- Adresse mail";
        }
        if(message !== ""){
          message = "Les informations de sécurité suivantes ont été modifiées :<br>" + message + "<br>Etes-vous sur de vouloir enregistrer les modifications?";
        }
      }else if (params.ctx === 'new') {
        message = "En validant ce formulaire, vous consentez que la coopérative scolaire<br>utilise vos données personnelles dans le cadre des commandes<br>de fournitures. Ces données ne seront pas transmises à des tiers.<br><br>Souhaitez-vous valider la création de votre compte?";
      }
      
      if(message === ""){
        appelValidation();
      }else{
        var modalOptions = {
          closeButtonText: 'Non',
          actionButtonText: 'Oui',
          headerText: 'Confirmation',
          bodyText: message
        };
        modalWindow.showModal({}, modalOptions).then(function (result){
          appelValidation();
        }).catch(angular.noop);
      }
    };
      
    $scope.modifierMdp = function(){
      appFunctions.loadComponent('changeMyPass', {id_uti: $scope.user.id_uti});
    };
    
    var appelValidation = function(){
      appFunctions.post('userManage',
        params.ctx,
        {user:$scope.user},
        function(data) {
          if(params.ctx === "new"){
            appFunctions.loadComponent('validUser', {id_uti: data.response.id_uti, compte_uti: $scope.user.compte_uti});
          }else{
            appFunctions.retourComposant();
          }
        },
        function(response) {
          
        }
      );
    };

    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    
    // si le contexte n'est pas définit
    if(!params.ctx){
      appFunctions.retourComposant();
    }
    $scope.contexte = params.ctx;

    // en cas de modification
    if(params.ctx === "mod" || params.ctx === "cha"){
      // appeler le service de chargement des données d'un utilisateur
      appFunctions.post('execRequest',
        (params.ctx === "mod" ? 'getUser' : 'getMyUser'),
        {id_uti:params.data.id_uti},
        function(data) {
          $scope.user = data.response.result[0];
          //$scope.user.pass_uti = '******';
          //$scope.user.pass_uti_save = $scope.user.pass_uti;
          $scope.user.mail_uti_save = $scope.user.mail_uti;
          $scope.user.comGroupe_uti = Number($scope.user.comGroupe_uti);
        },
        function(response) {

        }
      );
    }
    
    
    // Charger des fonction du composant dans l'appli
    //appFunctions.ajouterFonctionsComp('user', {testFnc:testFnc});
  }
]);


