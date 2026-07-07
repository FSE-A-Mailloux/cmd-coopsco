angular.module('fseCmdArticleMod',[
  'servicesMod'
])
.controller('fseCmdArticleCtrl', ['$scope', 'dataStorage', 'appFunctions', '$timeout', 'modalWindow',
  function ($scope, dataStorage, appFunctions, $timeout, modalWindow) {
   
    // variables
    $scope.commande = {};
    $scope.reduction = [];
    
    $scope.$watch('commande.articles',function(newvalue, oldvalue){
      if(newvalue){

        for(var i = 0; i < newvalue.length; i++){
          if(Number(newvalue[i].nbre_art) !== Number(oldvalue[i].nbre_art)){
            changerNbrArticle(i);
          }
        }
        

      }
    }, true);

    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };

    $scope.valider = function(){
      var modalOptions = {
        closeButtonText: 'Non',
        actionButtonText: 'Oui',
        headerText: 'Confirmation',
        bodyText: 'Etes-vous sur de vouloir valider cette commande?'
      };
      modalWindow.showModal({}, modalOptions).then(function (result){
        appFunctions.post('fseCmdManage',
          'new',
          {fse_commande:$scope.commande},
          function(data) {
            if(data.response.pdfBase64){
              appFunctions.saveBase64(data.response.pdfBase64, data.response.nomPdf);
            }
            data.response.pdfBase64 = null;
            if(!$scope.commande.gest){
              appFunctions.loadComponent('fseCmdConfirm', {commande: data.response});
            }else{
              appFunctions.retourAuComposant('fseListCmdEnCours');
            }
          },
          function(response) {

          }
        );
      }).catch(angular.noop);
    };
	
	$scope.getTotal = function(){
	  var total = 0;
      if(!$scope.commande.articles){
        return 0;
      }
	  for(var i = 0; i < $scope.commande.articles.length; i++){
		var article = $scope.commande.articles[i];
		total += ((article.prix_pafe - article.reduction) * article.nbre_art);
	  }
	  return total.toFixed(2) + " €";
	};
	
    var retour = function(){
      appFunctions.retourComposant({ctx:'mod', commande:$scope.commande});
    };
	
	var cumulerCotisation = function(){
	  // retrouver la ligne cotisation
	  for(var i= 0; i < $scope.commande.articles.length; i++){
		if($scope.commande.articles[i].code_art === 'COTIS'){
		  $scope.commande.articles[i].nbre_art = $scope.commande.nbrenf_cmd;
          changerNbrArticle(i);
		  break;
		}
	  }
	};
    
    var changerNbrArticle = function(idxLigne){
      var id_art = Number($scope.commande.articles[idxLigne].id_art);
      var reduction = 0;
      for(var j = 0; j < $scope.commande.reduction.length; j++){
        if(Number($scope.commande.reduction[j].id_art) === id_art){
          if(Number($scope.commande.reduction[j].nbr_prde) === 0 && reduction === 0){
            reduction = Number($scope.commande.reduction[j].reduction_prde);
          }
          if(Number($scope.commande.reduction[j].nbr_prde) === Number($scope.commande.articles[idxLigne].nbre_art)){
            reduction = Number($scope.commande.reduction[j].reduction_prde);
            break;
          }
        }
      }
      $scope.commande.articles[idxLigne].reduction = reduction;
    };

    // Récupération paramètres
    var params = appFunctions.getParametresComposant();
    $scope.commande = params.commande;
	
	if(!$scope.commande.articles){
	  // appeler le service de chargement des données
	  appFunctions.post('execRequest',
		'fseArticleList',
		{ctx:'cmd'},
		function(data) {
          $scope.commande.reduction = data.response.reduction;
		  $scope.commande.articles = data.response.result;
          
          // boucle crade pour changer le type d'une données
          var saveNum = "";
          var numCpt = 0;
          for(var i = 0; i < $scope.commande.articles.length; i++) {
            $scope.commande.articles[i].nbre_art = Number($scope.commande.articles[i].nbre_art);
            $scope.commande.articles[i].reduction = 0;
            $scope.commande.articles[i].traitBas = false;
            $scope.commande.articles[i].traitHaut = false;
            $scope.commande.articles[i].groupe = false;

            if($scope.commande.articles[i].ordre_art === saveNum){
              numCpt ++;
              if(numCpt === 2){
                $scope.commande.articles[i - 1].traitHaut = true;
                $scope.commande.articles[i - 1].groupe = true;
              }
              $scope.commande.articles[i].groupe = true;
            }else{
              if(numCpt > 1){
                $scope.commande.articles[i - 1].traitBas = true;
              }
              numCpt = 1;
            }
            saveNum = $scope.commande.articles[i].ordre_art;
          }
          
          if(numCpt > 1){
            $scope.commande.articles[$scope.commande.articles.length - 1].traitBas = true;
          }
          
          
		  // retrouver la ligne cotisation
		  cumulerCotisation();
		},
		function(response) {
		  $timeout(function(){appFunctions.retourComposant();}, 3000);
		}
	  );
	}else{
	  // retrouver la ligne cotisation
	  cumulerCotisation();
	}
	
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('fseCmdArticle', {
      RETOURARR:retour
    });

  }
]);


