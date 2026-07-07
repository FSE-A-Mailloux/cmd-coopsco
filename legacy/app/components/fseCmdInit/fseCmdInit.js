angular.module('fseCmdInitMod',[
  'servicesMod'
])
.controller('fseCmdInitCtrl', ['$scope', 'dataStorage', 'appFunctions', 
  function ($scope, dataStorage, appFunctions) {
   
    // variables
    $scope.commande = {
      mail_cmd: '',
      tel_cmd:'',
      nom_cmd :'',
      prenom_cmd:'',
      nbrenf_cmd:1,
      descenf_cmd:[{nom:'', prenom:'', classe:''}],
      gest: false,
      mail_gest: false
    };
    
    $scope.periode = {
      id_ann: -1,
      encours_ann: 0,
      ouverte_ann: 0
    };

    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };

    $scope.valider = function(){
      // basculer vers le composant d'ajout des articles
      appFunctions.loadComponent('fseCmdArticle', {commande: $scope.commande});
      
    };
    
    var resize = function(arr, size, defval) {
      while (arr.length > size) { arr.pop(); }
      while (arr.length < size) { arr.push({nom:'', prenom:'', classe:''}); }
    };
    $scope.modifNbrEnf = function(){
      if($scope.commande.nbrenf_cmd > 6){
        $scope.commande.nbrenf_cmd = 6;
      }
      resize($scope.commande.descenf_cmd, $scope.commande.nbrenf_cmd, {nom:'', prenom:'', classe:''} );
    };
    
    $scope.formatTel = function(pNum){
      return appFunctions.formatPhoneNumber(pNum);
    };
    
    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    if(!params.ctx){
      params.ctx = 'new';
    }
    if(params.ctx === 'add'){
      $scope.commande.gest = true;
    }
    
    // si le contexte n'est pas définit
    if(!params.ctx){
      appFunctions.retourComposant();
    }
    $scope.contexte = params.ctx;
    
    if(params.ctx === 'mod'){
      $scope.commande = params.commande;
    }
    
    
    // Vérifier si il y a une période en cours et si elle est ouverte
    appFunctions.post('execRequest',
      'fseAnneeEnCours',
      {},
      function(data) {
        // stat commandes annuelles
        $scope.periode = data.response.result[0];
      },
      function(response) {

      }
    );
    
    // Récupérer info utilisateur si contexte new
    if(params.ctx === 'new'){
      appFunctions.post('execRequest',
        'getMyUser',
        {},
        function(data) {
          // stat commandes annuelles
          var utilisateur = data.response.result[0];
          $scope.commande.mail_cmd = utilisateur.mail_uti;
          $scope.commande.nom_cmd = utilisateur.nom_uti;
          $scope.commande.prenom_cmd = utilisateur.prenom_uti;
          
        },
        function(response) {

        }
      );
    }
  }
]);


