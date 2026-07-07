/**
 * Déclaration du module fseListCmdEnCoursMod
 */
angular.module('fseConsolidationMod',[
  'servicesMod'
])
.controller('fseConsolidationCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    $scope.fse_consolidation = {
      periode: {},
      tabParFournisseur:[]
    };
    
    $scope.rowCollection = [];
    $scope.displayedCollection = [];
    
    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
    $scope.nbLot = function(row){
      row.nblot = Math.max(Math.ceil((row.nbr_acd + row.nb_fse - row.nb_sto) / row.lot_pafr),0);
      return row.nblot;
    };

    var getList = function(){
      appFunctions.post('execRequest',
        'fseConsolidationList',
        {id_ann:$scope.fse_consolidation.periode.id_ann},
        function(data) {
          // Parcourir la liste des articles séparer par fournisseur
          var id_fou = 0;
          var idx = -1;
          var article = {};
          for(var i = 0; i < data.response.result.length; i++) {
            article = data.response.result[i];
            article.nb_fse = 0;
            if(id_fou != article.id_fou){
              idx ++;
              $scope.fse_consolidation.tabParFournisseur[idx] = {
                design_fou: article.design_fou,
                articles: []
              };
              id_fou = article.id_fou;
            }
            article.cmdfam_art = Number(article.cmdfam_art);
            article.stock_art = Number(article.stock_art);
            article.nbr_acd = Number(article.nbr_acd);
            article.nb_sto = Number(article.nb_sto);
            article.lot_pafr = Number(article.lot_pafr);
            article.prix_pafr = Number(article.prix_pafr);
            article.nblot = 0;
            $scope.fse_consolidation.tabParFournisseur[idx].articles.push(article);
          }
        },
        function(response) {

        }
      );
    };

    // fonction de création de commande avec compte Gestionnaire FSE
    $scope.valider = function(){
      var modalOptions = {
        closeButtonText: 'Non',
        actionButtonText: 'Oui',
        headerText: 'Confirmation',
        bodyText: 'Etes-vous sur de vouloir valider cette consolidation?'
      };
      modalWindow.showModal({}, modalOptions).then(function (result){
        appFunctions.post('fseConsolidationManage',
          'new',
          {fse_consolidation:$scope.fse_consolidation},
          function(data) {
            if(data.response.pdfBase64){
              appFunctions.saveBase64(data.response.pdfBase64, data.response.nomPdf);
            }
            data.response.pdfBase64 = null;

          },
          function(response) {

          }
        );
      }).catch(angular.noop);
    };

    // Récupérer la période en cours.
    appFunctions.post('execRequest',
      'fseAnneeEnCours',
      {},
      function(data) {
        $scope.fse_consolidation.periode = data.response.result[0];

        // vérifier si période ouverte
        if($scope.fse_consolidation.periode.ouverte_ann === "1"){
          // Afficher un message demandant confirmation cloture période
          var modalOptions = {
            closeButtonText: 'Non',
            actionButtonText: 'Oui',
            headerText: 'Confirmation',
            bodyText: 'La période en cours est encore ouverte, une consolidation sur une période ouverte n\'est pas conseillé, souhaitez-vous fermer la période?'
          };
          modalWindow.showModal({}, modalOptions).then(function (result){
            // appeler le service de fermeture de période
            appFunctions.post('fseAnneeManage',
              'ouvferm',
              {fse_annee:{id_ann:$scope.fse_consolidation.periode.id_ann}},
              function(data) {
                getList();
              },
              function(response) {

              }
            );
          },
          function(response) {
            // récupérer la liste des articles
            getList();
          }).catch(angular.noop);
        }else{
          // récupérer la liste des articles
          getList();
        }
        
      },
      function(response) {

      }
    );
    
  }
]);


