angular.module('fseCmdDetailMod',[
  'servicesMod'
])
.controller('fseCmdDetailCtrl', ['$scope', 'dataStorage', 'appFunctions', 
  function ($scope, dataStorage, appFunctions) {
   
    // variables
    $scope.commande = {
      num_cmd:'',
      mail_cmd: '',
      tel_cmd:'',
      nom_cmd :'',
      prenom_cmd:'',
      dtcre_cmd:'',
      total:'',
      mtregle_cmd:'',
      descenf:[],
      articles:[]
    };

    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    
    // Vérifier si il y a une période en cours et si elle est ouverte
    var service = '';
    if( params.ctx === 'mod')
      service = 'fseCmdDetail';
    if( params.ctx === 'modUti')
      service = 'fseCmdDetailUti';
    
    appFunctions.post('execRequest',
      service,
      {id_cmd:params.data.id_cmd},
      function(data) {
        // stat commandes annuelles
        $scope.commande = data.response.commande[0];
        $scope.commande.descenf = data.response.descenf;
        $scope.commande.articles = data.response.articles;
      },
      function(response) {

      }
    );
  }
]);

