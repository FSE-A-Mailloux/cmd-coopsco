/**
 * Déclaration du module etatListMod
 */
angular.module('etatListMod',[
  'servicesMod'
])
.controller('etatListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    //$scope.rowCollection = [];
    //$scope.displayedCollection = [];
    
    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };
    
    $scope.doubleclick = function(row){
      if(row){
        imprimer(row);
      }
    };
    
    var getList = function(){
      appFunctions.post('execRequest',
        'etatList',
        {},
        function(data) {
          $scope.rowCollection = data.response.result;
          $scope.displayedCollection = [].concat($scope.rowCollection);
        },
        function(response) {

        }
      );
    };

    // Récupération paramètres
    //var params = appFunctions.getParametresComposant();
    
    var imprimer = function(row){
      if(!row){
        row = appFunctions.getRowSelectTable($scope.displayedCollection, 'Vous devez sélectionner un état');
      }
      if(row){
        var modalOptions = {
          closeButtonText: 'Non',
          actionButtonText: 'Oui',
          headerText: 'Confirmation',
          bodyText: "Etes-vous sur de vouloir générer cet état?"
        };
        modalWindow.showModal({}, modalOptions).then(function (result){
          appFunctions.post('etatManage',
            'print',
            {etat:{cd_epm:row.cd_epm}},
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
      }
    };
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('etatList', {
      IMPRIMER:imprimer
    });

    $scope.rowTitle = [
      {
        name:'cd_epm',
        lib:'Code',
        type:'text',
        format:'' 
      },{
        name:'lib_epm',
        lib:'Libellé',
        type:'text',
        format:'' 
      }
    ];

    $scope.refreshList = function(){
      getList();
    };
    
    getList();
    
  }
]);


