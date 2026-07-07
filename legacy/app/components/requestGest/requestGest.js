/**
 * Déclaration du module userMod
 */
angular.module('requestGestMod',[
  'servicesMod'
])
.controller('requestGestCtrl', ['$scope', 'dataStorage', 'appFunctions', '$timeout', 
  function ($scope, dataStorage, appFunctions, $timeout) {

    // variables
    $scope.request = {
      id_req: -1,
      code_req:'',
      lib_req:'',
      result_req:'',
      select_req:''
    };
    
	$scope.editorOptions = {
		lineWrapping : false,
		lineNumbers: true,
		//readOnly: 'nocursor',
		mode: 'mysql'
	};
    
    
    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
    $scope.valider = function(){
      appFunctions.post('requestManage',
        params.ctx,
        {request:$scope.request},
        function(data) {
          appFunctions.retourComposant();
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
    if(params.ctx === "mod"){
      // appeler le service de chargement des données d'un utilisateur
      appFunctions.post('execRequest',
        'getRequest',
        {id_req:params.data.id_req},
        function(data) {
          $scope.request = data.response.result[0];
        },
        function(response) {
          $timeout(function(){appFunctions.retourComposant();}, 3000);
        }
      );
    }
    

  }
]);


