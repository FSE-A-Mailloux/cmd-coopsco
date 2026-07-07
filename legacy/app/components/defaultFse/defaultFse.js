angular.module('defaultFseMod',[
  'servicesMod',
  'chart.js'
])
.controller('defaultFseCtrl', ['$scope', 'dataStorage', 'appFunctions', '$timeout', 'modalWindow',
  function ($scope, dataStorage, appFunctions, $timeout, modalWindow) {
   
    // variables
    $scope.stat = {
      cmdAnnuelles: {// stat commandes annuelles
        labels:[],
        series:['Créées','Validées', 'Annulées'],
        data:[[],[],[]],
        options: {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero:true,
                callback: function(value, index, values) {
                    if (Math.floor(value) === value) {
                        return value;
                    }
                }
              }
            }]
          }
        }
      },
      cmdEnCours:{
        labels:["Total non validé", "Total validé"],
        data:[]
      },
      messages:[]
    };

    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };

    var getStat = function(){
      appFunctions.post('execRequest',
        'fseStat',
        {},
        function(data) {
          // stat commandes annuelles
          for(var i = 0; i < data.response.cmdAnnuelles.length; i++){
            $scope.stat.cmdAnnuelles.labels.push(data.response.cmdAnnuelles[i]['lib_ann']);
            $scope.stat.cmdAnnuelles.data[0].push(Number(data.response.cmdAnnuelles[i]['nb_cmd']));
            $scope.stat.cmdAnnuelles.data[1].push(Number(data.response.cmdAnnuelles[i]['nb_valid_cmd']));
            $scope.stat.cmdAnnuelles.data[2].push(Number(data.response.cmdAnnuelles[i]['nb_annul_cmd']));
          }
          
          $scope.stat.cmdEnCours.data[0] = Number(data.response.cmdEnCours[0]['tot_nonvalid']);
          $scope.stat.cmdEnCours.data[1] = Number(data.response.cmdEnCours[0]['tot_valid']);
        },
        function(response) {

        }
      );
    };
	

    
    // récupération information statistique
    getStat();
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('defaultFse', {
      
    });

  }
]);


