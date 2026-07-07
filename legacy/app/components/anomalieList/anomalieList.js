/**
 * Déclaration du module compListMod
 */
angular.module('anomalieListMod',[
  'servicesMod'
])
.controller('anomalieListCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    //$scope.rowCollection = [];
    //$scope.displayedCollection = [];
    
    // fonctions

    var getList = function(){
      appFunctions.post('execRequest',
        'anomalieList',
        {},
        function(data) {
          $scope.selectedRow = appFunctions.loadSelectedRow();
          $scope.rowCollection = data.response.result;
          $scope.displayedCollection = [].concat($scope.rowCollection);
        },
        function(response) {

        }
      );
    };

    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('anomalieList', {

    });


    $scope.rowTitle = [
      {
        name:'date_ano_chaine',
        lib:'Date',
        type:'text',
        format:'' 
      },{
        name:'action_ano',
        lib:'Action',
        type:'text',
        format:'' 
      },{
        name:'contexte_ano',
        lib:'Contexte',
        type:'text',
        format:''
      },{
        name:'erreur_ano_100',
        lib:'Erreur...',
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


