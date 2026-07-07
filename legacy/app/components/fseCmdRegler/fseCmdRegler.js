angular.module('fseCmdReglerMod',[
  'servicesMod'
])
.controller('fseCmdReglerCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
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
      mtreglenum_cmd:0,
      //mtreglement:0,
      validation:1,
      reglement:[]
    };
    $scope.listeBanques = [];

    var b_validationCoche = false;

    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
	$scope.getTotal = function(){
	  var total = 0;
      if(!$scope.commande.reglement){
        return 0;
      }
	  for(var i = 0; i < $scope.commande.reglement.length; i++){
		var reglement = $scope.commande.reglement[i];
        if(reglement.dateannul_cmdrglt === null)
          total += (reglement.montant_cmdrglt);
	  }
      
      if( total == 0){
        $scope.commande.validation = '0';
        b_validationCoche = false;
      }else{
        if(!b_validationCoche){
          $scope.commande.validation = '1';
          b_validationCoche = true;
        }
      }
        
	  return total.toFixed(2);
	};
    
    $scope.ajouterReglement = function() {
      $scope.commande.reglement.push({
        id_cmdrglt:null,
        id_cmd:null,
        type_cmdrglt:"1",
        montant_cmdrglt:0,
        montant_cmdrglt_euro:'',
        date_cmdrglt:null,
        dateannul_cmdrglt:null,
        chequenomporteur_cmd_rglt:'',
        id_bqe:null,
        nom_bqe:null,
        date_cmdrglt_format:'',
        type_cmdrglt_chaine:''
      });
    };
    
    $scope.supprimerReglement = function(pIndex) {
      if($scope.commande.reglement[pIndex].id_cmdrglt === null){
        $scope.commande.reglement.splice(pIndex, 1);
      }else{
        if($scope.commande.reglement[pIndex].dateannul_cmdrglt === null)
          $scope.commande.reglement[pIndex].dateannul_cmdrglt = '-';
        else
          $scope.commande.reglement[pIndex].dateannul_cmdrglt = null;
      }
    };
    
    $scope.rechercheBanque = function(pIndex) {
      $scope.commande.reglement[pIndex].id_bqe = null;
      for (var i = 0; i < $scope.listeBanques.length; i++){
        // look for the entry with a matching `code` value
        if ($scope.listeBanques[i].nom_bqe === $scope.commande.reglement[pIndex].nom_bqe){
          $scope.commande.reglement[pIndex].id_bqe = $scope.listeBanques[i].id_bqe;
          break;
        }
      }
    };
    
    
    
    $scope.valider = function(){
      var modalOptions = {
        closeButtonText: 'Non',
        actionButtonText: 'Oui',
        headerText: 'Confirmation',
        bodyText: ($scope.commande.validation === '1' ? 'La commande sera validée. ' : '') + 'Etes-vous sur des règlements?' 
      };
      modalWindow.showModal({}, modalOptions).then(function (result){
        appFunctions.post('fseCmdManage',
          'regler',
          {fse_commande:$scope.commande},
          function(data) {
            appFunctions.retourAuComposant('fseListCmdEnCours');
          },
          function(response) {

          }
        );
      }).catch(angular.noop);
    };
    
    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    
    // Vérifier si il y a une période en cours et si elle est ouverte
    appFunctions.post('execRequest',
      'fseCmdDetail',
      {id_cmd:params.data.id_cmd},
      function(data) {
        // stat commandes annuelles
        $scope.commande = data.response.commande[0];
        $scope.commande.validation = "1";
        $scope.commande.reglement = data.response.reglement;
        
        for(var i = 0; i < $scope.commande.reglement.length; i++){
          $scope.commande.reglement[i].montant_cmdrglt = Number($scope.commande.reglement[i].montant_cmdrglt);
        }
      },
      function(response) {

      }
    );
    
    var combos = appFunctions.getCombosComposant();
    $scope.listeBanques = [].concat(combos.listeBanques);
    
  }
]);

