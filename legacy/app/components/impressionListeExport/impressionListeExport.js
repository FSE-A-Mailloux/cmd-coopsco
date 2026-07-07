/**
 * Déclaration du module userMod
 */
angular.module('impressionListeExportMod',[
  'servicesMod'
])
.controller('impressionListeExportCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', '$filter', 
  function ($scope, dataStorage, appFunctions, modalWindow, $filter) {
   
    // variables
    $scope.ImpressionListe = {
      id_ilp: 0,
      TabChampsDispo: [],
      TabChampsExport: []
    };
    
    $scope.TabImpressionListeParam = [];
    
    // Utilisation du datepicker 
    $scope.dtformat = 'dd/MM/yyyy';
    
    $scope.dateOptions = {
      formatYear: 'yy',
      startingDay: 1
    };
    $scope.dtopen = function(e, numDate, index) {
      e.preventDefault();
      e.stopPropagation();
      if(numDate === 1){
        $scope.ImpressionListe.TabChampsExport[index].date1opened = true;
      }else if(numDate === 2){
        $scope.ImpressionListe.TabChampsExport[index].date2opened = true;
      }
    };
    
    
    $scope.changerParam = function(){
      appFunctions.post('execRequest',
        'impressionListeChampList',
        {id_ilp: $scope.ImpressionListe.id_ilp},
        function(data) {
          $scope.ImpressionListe.TabChampsExport = [];
          $scope.ImpressionListe.TabChampsDispo = data.response.result;
          for(var i = 0; i < $scope.ImpressionListe.TabChampsDispo.length; i++){
            $scope.ImpressionListe.TabChampsDispo[i].select = 0;
          }
        },
        function(response) {

        }
      );
    };
    
    $scope.toutAjouter = function(){
      for(var i = 0; i < $scope.ImpressionListe.TabChampsDispo.length; i++){
        if($scope.ImpressionListe.TabChampsDispo[i].select === 0){
          $scope.ImpressionListe.TabChampsDispo[i].select = 1;
          $scope.ImpressionListe.TabChampsExport.push({
            id_ilc: $scope.ImpressionListe.TabChampsDispo[i].id_ilc,
            libelle_ilc: $scope.ImpressionListe.TabChampsDispo[i].libelle_ilc,
            colonne_ilc: $scope.ImpressionListe.TabChampsDispo[i].colonne_ilc,
            type_ilc: $scope.ImpressionListe.TabChampsDispo[i].type_ilc,
            filtre: '-',
            valeur1: '',
            valeur2: '',
            date1opened: false,
            date1value: '',
            date2opened: false,
            date2value: ''
          });
        }
      }
    };
    
    
    
    $scope.toutRetirer = function(){
      for(var i = 0; i < $scope.ImpressionListe.TabChampsDispo.length; i++){
        $scope.ImpressionListe.TabChampsDispo[i].select = 0;
      }
      $scope.ImpressionListe.TabChampsExport = [];
    };
    
    $scope.ajouter = function(pColonne_ilc){
      for(var i = 0; i < $scope.ImpressionListe.TabChampsDispo.length; i++){
        if($scope.ImpressionListe.TabChampsDispo[i].colonne_ilc === pColonne_ilc && $scope.ImpressionListe.TabChampsDispo[i].select === 0){
          $scope.ImpressionListe.TabChampsDispo[i].select = 1;
          $scope.ImpressionListe.TabChampsExport.push({
            id_ilc: $scope.ImpressionListe.TabChampsDispo[i].id_ilc,
            libelle_ilc: $scope.ImpressionListe.TabChampsDispo[i].libelle_ilc,
            colonne_ilc: $scope.ImpressionListe.TabChampsDispo[i].colonne_ilc,
            type_ilc: $scope.ImpressionListe.TabChampsDispo[i].type_ilc,
            filtre: '-',
            valeur1: '',
            valeur2: '',
            date1opened: false,
            date1value: '',
            date2opened: false,
            date2value: ''
          });
          break;
        }
      }
    };
    
    $scope.retirer = function(pColonne_ilc){
      for(var i = 0; i < $scope.ImpressionListe.TabChampsDispo.length; i++){
        if($scope.ImpressionListe.TabChampsDispo[i].colonne_ilc === pColonne_ilc && $scope.ImpressionListe.TabChampsDispo[i].select === 1){
          $scope.ImpressionListe.TabChampsDispo[i].select = 0;
          break;
        }
      }
      for(var i = 0; i < $scope.ImpressionListe.TabChampsExport.length; i++){
        if($scope.ImpressionListe.TabChampsExport[i].colonne_ilc === pColonne_ilc){
          $scope.ImpressionListe.TabChampsExport.splice(i, 1);
          break;
        }
      }
    };
    
    
    $scope.valider = function(){
      // Compter le nombre champs dans l'export
      if($scope.ImpressionListe.TabChampsExport.length === 0){
        var modalOptions = {
          closeButtonText: 'OK',
          actionButtonText: '',
          headerText: 'Information',
          bodyText: 'Il faut au moins un champs exporté !'
        };
        modalWindow.showModal({}, modalOptions).then(function (result){}).catch(angular.noop);

      }else{
      
        for(var i = 0; i < $scope.ImpressionListe.TabChampsExport.length; i++){
          if($scope.ImpressionListe.TabChampsExport[i].type_ilc === 'DATE' || $scope.ImpressionListe.TabChampsExport[i].type_ilc === 'DATETIME'){
            $scope.ImpressionListe.TabChampsExport[i].valeur1 = $filter('date')($scope.ImpressionListe.TabChampsExport[i].date1value, 'yyyy-MM-dd');
            $scope.ImpressionListe.TabChampsExport[i].valeur2 = $filter('date')($scope.ImpressionListe.TabChampsExport[i].date2value, 'yyyy-MM-dd');
          }
        }
      
        appFunctions.post('impressionListeManage',
          'requestCount',
          {ImpressionListe:$scope.ImpressionListe},
          function(data) {
            
            if(Number(data.response.NbResult) === 0){
              var modalOptions = {
                closeButtonText: 'OK',
                actionButtonText: '',
                headerText: 'Information',
                bodyText: 'La requête a retourné 0 résultat !'
              };
              modalWindow.showModal({}, modalOptions).then(function (result){}).catch(angular.noop);
              
            }else{
              var modalOptions = {
                closeButtonText: 'Non',
                actionButtonText: 'Oui',
                headerText: 'Confirmation',
                bodyText: "La requête a retourné "+data.response.NbResult+" résultat(s) !\nSouhaitez-vous télécharger le fichier excel ?"
              };
              modalWindow.showModal({}, modalOptions).then(function (result){
                appFunctions.post('impressionListeManage',
                  'requestExport',
                  {ImpressionListe:$scope.ImpressionListe},
                  function(data) {
                    if(data.response.excelBase64){
                      appFunctions.saveBase64(data.response.excelBase64, data.response.excelNom);
                    }
                    data.response.excelBase64 = null;
                  },
                  function(response) {

                  }
                );
              }).catch(angular.noop);
            }

            
            
          },
          function(response) {

          }
        );
      }
    };

    
    var getImpressionListeParamList = function(){
      appFunctions.post('execRequest',
        'impressionListeParamList',
        {},
        function(data) {
          $scope.TabImpressionListeParam = data.response.result;
        },
        function(response) {

        }
      );
    };
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('impressionListeExport', {

    });

    
    getImpressionListeParamList();
  }
]);


