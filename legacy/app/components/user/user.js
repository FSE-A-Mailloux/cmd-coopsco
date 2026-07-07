/**
 * Déclaration du module userMod
 */
angular.module('userMod',[
  'servicesMod'
])
.controller('userCtrl', ['$scope', 'dataStorage', 'appFunctions', 
  function ($scope, dataStorage, appFunctions) {
   
    // variables
    $scope.user = {
      id: -1,
      compte: '',
      mdp:'',
      nom:'',
      prenom:'',
      email:'',
      recaptchaResponse: ''
    };
    
    $scope.captchaPublicKey = dataStorage.params.captchaPublicKey;

    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
    $scope.captchaActif = function(){
      return (dataStorage.params.captchaPublicKey !== "" && dataStorage.appDatas.token === "");
    };

    $scope.valider = function(){
      appFunctions.post('userManage',
        params.ctx,
        {user:$scope.user},
        function(data) {
          if(params.ctx === "new"){
            appFunctions.loadComponent('validUser', {id_uti: data.response.id_uti, compte_uti: $scope.user.compte});
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
        'user',
        {id_uti:params.id},
        function(data) {
          $scope.user = {
            id: data.result.id_uti,
            compte: data.result.compte_uti,
            mdp:'',
            nom:data.result.nom_uti,
            prenom:data.result.prenom_uti,
            email:'',
            recaptchaResponse: ''
          };
        },
        function(response) {

        }
      );
      

    }
    
    
    // Charger des fonction du composant dans l'appli
    //appFunctions.ajouterFonctionsComp('user', {testFnc:testFnc});
  }
]);


