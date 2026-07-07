/**
 * Déclaration du module userMod
 */
angular.module('etatparamGestMod',[
  'servicesMod'
])
.controller('etatparamGestCtrl', ['$scope', 'dataStorage', 'appFunctions', 
  function ($scope, dataStorage, appFunctions) {
   
    // variables
    $scope.etatparam = {
      cd_epm: '',
      lib_epm:'',
      code_mhl:'',
      lib_mhl:'',
      code_req:'',
      lib_req:'',
      jsonTest:{}
    };
    
    $scope.jsonText = '';
    
	$scope.editorOptions = {
		lineWrapping : false,
		lineNumbers: true,
		//readOnly: 'nocursor',
		mode: 'xml',
        htmlMode: true
	};
    
    $scope.editorOptionsJSON = {
		lineWrapping : false,
		lineNumbers: true,
		//readOnly: 'nocursor',
		mode: 'javascript'
	};
    
    $scope.valider = function(){
      appFunctions.post('etatparamManage',
        params.ctx,
        {etatparam:$scope.etatparam},
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
        'getEtatparam',
        {cd_epm:params.data.cd_epm},
        function(data) {
          $scope.etatparam = data.response.result[0];
        },
        function(response) {
          
        }
      );
    }
    
    var tester = function(){
      $scope.etatparam.jsonTest = eval("(" + ($scope.jsonText || "{}" ) + ")");
      appFunctions.post('etatparamManage',
        'test',
        {etatparam:$scope.etatparam},
        function(data) {
          if(data.response.pdfBase64){
            appFunctions.saveBase64(data.response.pdfBase64, data.response.nomPdf);
          }
          data.response.pdfBase64 = null;
        },
        function(response) {
        }
      );
    };
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('etatparamGest', {
      TESTER:tester
    });
    
  }
]);


