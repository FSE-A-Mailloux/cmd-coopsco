/**
 * Déclaration du module userMod
 */
angular.module('impressionListeParamGestMod',[
  'servicesMod'
])
.controller('impressionListeParamGestCtrl', ['$scope', 'dataStorage', 'appFunctions', 'modalWindow', 
  function ($scope, dataStorage, appFunctions, modalWindow) {
   
    // variables
    $scope.impression_liste_param = {
      id_ilp: 0,
      lib_ilp: '',
      id_req: 0,
      actif_ilp: 0,
      tab_champs: []
    };
    
    $scope.$watch('impression_liste_param.id_req',function(newvalue, oldvalue){
      if(newvalue && (params.ctx === 'add' || oldvalue !== 0)){
        $scope.tab_champs = [];
        appFunctions.post('impressionListeManage',
          'getChamps',
          {id_req: newvalue},
          function(data) {
            
            if(data.response.erreur !== ''){
              var modalOptions = {
                closeButtonText: '',
                actionButtonText: 'Ok',
                headerText: 'Information',
                bodyText: data.response.erreur
              };
              modalWindow.showModal({}, modalOptions).then(function (result){}).catch(angular.noop);
            }else{
              $scope.impression_liste_param.tab_champs = [];
              for(var i = 0; i < data.response.result.length; i++){

                let columDef = data.response.result[i];
                let champ = {
                  colonne_ilc: columDef.name,
                  ordre_ilc: i,
                  type_ilc: '',
                  libelle_ilc: ''
                };
                
                if(columDef.native_type.indexOf('LONG') >= 0 || columDef.native_type.indexOf('INT') >= 0 || columDef.native_type.indexOf('TINY') >= 0 || columDef.native_type.indexOf('DOUBLE') >= 0 || columDef.native_type.indexOf('NEWDECIMAL') >= 0 ){
                  champ.type_ilc = 'NUMBER';
                }else if(columDef.native_type.indexOf('STRING') >= 0 || columDef.native_type.indexOf('TEXT') >= 0 || columDef.native_type.indexOf('BLOB') >= 0){
                  champ.type_ilc = 'TEXT';
                }else if(columDef.native_type.indexOf('DATETIME') >= 0){
                  champ.type_ilc = 'DATETIME';
                }else if(columDef.native_type.indexOf('DATE') >= 0){
                  champ.type_ilc = 'DATE';
                }
                $scope.impression_liste_param.tab_champs.push(champ);
              }
            }
          },
          function(response) {
          }
        );
      }
    });
    
    
    $scope.valider = function(){
      appFunctions.post('impressionListeManage',
        params.ctx,
        {impression_liste_param:$scope.impression_liste_param},
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
        'getImpressionListeParam',
        {id_ilp:params.data.id_ilp},
        function(data) {
          $scope.impression_liste_param = data.response.result[0];
          $scope.impression_liste_param.actif_ilp = Number($scope.impression_liste_param.actif_ilp);
          
          $scope.impression_liste_param.tab_champs = data.response.tab_champs;
        },
        function(response) {
          
        }
      );
    }
    
    
    // Charger des fonction du composant dans l'appli
    appFunctions.ajouterFonctionsComp('impressionListeParamGest', {
      
    });
    
  }
]);


