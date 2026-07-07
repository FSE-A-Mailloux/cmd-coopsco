/**
 * Déclaration du module userMod
 */
angular.module('mailMod',[
  'servicesMod'
])
.controller('mailCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow',  
  function ($scope, dataStorage, appFunctions, modalWindow) {

    // variables
    $scope.mail = {
      code_mhl:'',
      typeDest: 'LIBRE',
      destinataire: '',
      masque:true,
      objet:'',
      corps:''
    };
    $scope.destFige = false;
    $scope.listModele = [];
    $scope.corpsChange = true;
    
    var getCorpsMail = function(){
      var editor = CKEDITOR.instances.editorCorps;
      var corpsMail = editor.getData();
      $scope.corpsChange = (corpsMail !== $scope.mail.corps);
      $scope.mail.corps = editor.getData();
    };
    
    var envoyerMail = function(){
      
      appFunctions.post('mailManage',
        'envoyer',
        {mail:$scope.mail},
        function(data) {
          var modalOptions = {
            closeButtonText: '',
            actionButtonText: 'Ok',
            headerText: 'Mail envoyé',
            bodyText: 'Le mail a bien été envoyé.'
          };
          modalWindow.showModal({}, modalOptions).then(function (result){
          }).catch(angular.noop);
          appFunctions.retourComposant();

        },
        function(response) {
          
        }
      );
    };
    
    $scope.changerTypeDest = function(){
      if($scope.mail.typeDest === 'GRO'){
        $scope.mail.masque = true;
      }
    };
    
    // fonctions
    $scope.modifierTemplate = function(){
      $scope.corpsChange = true;
    };
    
    $scope.previsualiser = function(){
      getCorpsMail();
      appFunctions.post('mailManage',
        'previsualiser',
        {mail:$scope.mail},
        function(data) {
          //appFunctions.retourComposant();
          if(data.response.corpsHTML){
            var win = window.open("", "Prévisualisation mail", "toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=780,height=500");
            win.document.body.innerHTML = data.response.corpsHTML;
            $scope.corpsChange = false;
          };
        },
        function(response) {
          
        }
      );
    };
    
    $scope.valider = function(){
      getCorpsMail();
      
      // si il n'y a pas eu de prévisualisation avant l'envoi suite changement
      if($scope.corpsChange){
        var modalOptions = {
          closeButtonText: 'Non',
          actionButtonText: 'Oui',
          headerText: 'Confirmation',
          bodyText: "Des modifications ont été apportées au mail.<br>Souhaitez-vous l'envoyer sans prévisualisation?"
        };
        modalWindow.showModal({}, modalOptions).then(function (result){
          envoyerMail();
        }).catch(angular.noop);
      }else{
        envoyerMail();
      }
    };
    


    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    
    if(params.typeDest){
      $scope.mail.destFige = true;
      $scope.mail.typeDest = params.typeDest;
      $scope.mail.destinataire = params.destinataire;
    }

    // récupérer liste des modèles éligible
    appFunctions.post('execRequest',
      'templateMailList',
      {},
      function(data) {
        $scope.listModele = data.response.result;
        $scope.mail.code_mhl = $scope.listModele[0].code_mhl;
      },
      function(response) {
        $timeout(function(){appFunctions.retourComposant();}, 3000);
      }
    );

    
    // Charger des fonction du composant dans l'appli
    //appFunctions.ajouterFonctionsComp('user', {testFnc:testFnc});
  }
]);


