/**
 * Déclaration du module userMod
 */
angular.module('modeleHtmlGestMod',[
  'servicesMod'
])
.controller('modeleHtmlGestCtrl', ['$scope', 'dataStorage', 'appFunctions', '$timeout', 'modalWindow', 
  function ($scope, dataStorage, appFunctions, $timeout, modalWindow) {

    // variables
    $scope.modeleHtml = {
      code_mhl:'',
      lib_mhl:'',
      type_mhl:'',
      verrou_mhl:0,
      modele_mhl:'',
      jsonTest: {}
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
    
    
    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
    $scope.valider = function(){
      appFunctions.post('modeleHtmlManage',
        params.ctx,
        {modeleHtml:$scope.modeleHtml},
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
        'getModeleHtml',
        {code_mhl:params.data.code_mhl},
        function(data) {
          $scope.modeleHtml = data.response.result[0];
        },
        function(response) {
          $timeout(function(){appFunctions.retourComposant();}, 3000);
        }
      );
    }

    var testerModele = function(){
      $scope.modeleHtml.jsonTest = eval("(" + ($scope.jsonText || "{}" ) + ")");
      // Si mail
      appFunctions.post('modeleHtmlManage',
        'test',
        {modeleHtml:$scope.modeleHtml},
        function(data) {
          if($scope.modeleHtml.type_mhl === 'MAIL'){
            // Afficher une popup
            var modalOptions = {
              closeButtonText: '',
              actionButtonText: 'Ok',
              headerText: 'Information',
              bodyText: 'Un mail de test vient de vous être envoyé'
            };
            modalWindow.showModal({}, modalOptions).catch(angular.noop);
          }else{
            if(data.response.pdfBase64){
              //$window.open("data:application/pdf;base64, " + data.response.pdfBase64);
              
              appFunctions.saveBase64(data.response.pdfBase64, $scope.modeleHtml.code_mhl + '.pdf');
              
            }
          }

        },
        function(response) {


        }
      );
    }
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('modeleHtmlGest', {
      TESTER:testerModele
    });

  }
]);


