/**
 * Déclaration du module userMod
 */
angular.module('moduleParamMod',[
  'servicesMod'
])
.controller('moduleParamCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
  function ($scope, dataStorage, appFunctions, modalWindow) {

    // variables
    $scope.moduleParam = {
      tabGroupeParam: []
    };
    $scope.editStructure = false;
    
    $scope.ajouterGroupe = function(){
      // Ajouter un groupe dans le tableau
      $scope.moduleParam.tabGroupeParam.push({
        nomGroupe:'',
        tabParam:[]
      });
    };
    
    $scope.supprimerGroupe = function(pIdxGroupe){
      // Demander la confirmation
      var modalOptions = {
        closeButtonText: 'Non',
        actionButtonText: 'Oui',
        headerText: 'Confirmation',
        bodyText: 'Etes-vous sur de vouloir supprimer ce groupe de paramètre?'
      };
      modalWindow.showModal({}, modalOptions).then(function (result){
        $scope.moduleParam.tabGroupeParam.splice(pIdxGroupe,1);
      }).catch(angular.noop);
    };

    $scope.monterGroupe = function(pIdxGroupe){
      if(pIdxGroupe > 0){
        $scope.moduleParam.tabGroupeParam.move(pIdxGroupe,pIdxGroupe-1);
      }
    };
    
    $scope.descendreGroupe = function(pIdxGroupe){
      if(pIdxGroupe <= $scope.moduleParam.tabGroupeParam.length){
        $scope.moduleParam.tabGroupeParam.move(pIdxGroupe,pIdxGroupe+1);
      }
    };

    
    $scope.ajouterParam = function(pIdxGroupe){
      $scope.moduleParam.tabGroupeParam[pIdxGroupe].tabParam.push({
        code:'',
        libelle:'',
        type:'text',
        valeur:'',
        cible:'CS'
      });
    };
    
    $scope.supprimerParam = function(pIdxGroupe, pIdxParam){
      $scope.moduleParam.tabGroupeParam[pIdxGroupe].tabParam.splice(pIdxParam,1);
    };
        
    var valider = function(){
      appFunctions.post('moduleManage',
        'params',
        {moduleParams:{cd_mod:$scope.cd_mod, param_mod:JSON.stringify($scope.moduleParam)}},
        function(data) {
          appFunctions.retourComposant();
        },
        function(response) {
          
        }
      );
    };
    
    var structurer = function(){
      $scope.editStructure = !$scope.editStructure;
    };
    
    
    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    
    // si le contexte n'est pas définit
    if(!params.ctx){
      appFunctions.retourComposant();
    }
    
    $scope.contexte = params.ctx;
    $scope.cd_mod = params.data.cd_mod;
    $scope.titreModule = "[" + params.data.cd_mod + "] " + params.data.lib_mod;

    // en cas de modification
    if(params.ctx === "mod"){
      // appeler le service de chargement des données
      appFunctions.post('execRequest',
        'getModuleParam',
        {cd_mod:params.data.cd_mod},
        function(data) {
          var jsonText = data.response.result[0].param_mod;
          if( jsonText === '' ){
            $scope.moduleParam = {
              tabGroupeParam: []
            };
          }else{
            $scope.moduleParam = JSON.parse(data.response.result[0].param_mod);
          }
          //$scope.moduleParam = data.response.result[0];
        },
        function(response) {
        }
      );
    }

    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('moduleParam', {
      VALIDER:valider,
      STRUCTURER:structurer
    });

  }
]);


