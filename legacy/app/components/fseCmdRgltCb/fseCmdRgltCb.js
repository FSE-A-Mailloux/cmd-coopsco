angular.module('fseCmdRgltCbMod',[
  'servicesMod'
])
.controller('fseCmdRgltCbCtrl', ['$scope', 'dataStorage', 'appFunctions', '$timeout', 'modalWindow',
  function ($scope, dataStorage, appFunctions, $timeout, modalWindow) {
   
    $scope.fse_commande = {
      num_cmd: "",
      code: "",
      url: ""
    };
   
    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };

    $scope.startPayment = function(){
      
    };

    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    $scope.fse_commande.num_cmd = params.data.num_cmd;
    
    // Vérifier si il y a une période en cours et si elle est ouverte
    appFunctions.post('fseCmdManage',
      'cbstart',
      {fse_commande:{id_cmd:params.data.id_cmd}},
      function(data) {
        
        $scope.fse_commande.code = data.response.code;
        if($scope.fse_commande.code === 'URL'){
          $scope.fse_commande.url = data.response.url;
        }

      },
      function(response) {

      }
    );

  }
]);


