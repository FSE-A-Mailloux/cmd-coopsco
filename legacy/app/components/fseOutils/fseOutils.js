/**
 * Déclaration du module fseOutilsMod
 */
angular.module('fseOutilsMod',[
  'servicesMod'
])
.controller('fseOutilsCtrl', ['$scope', 'dataStorage', 'appFunctions', 
  function ($scope, dataStorage, appFunctions) {

    var nbGroupe = 0;
    var tabIdCmd = [];
    var compteur = 0;
    
    $scope.genererPdfCommandesCb = function(){
      // Récupérer le tableau d'identifiant min/max
      
      appFunctions.post('fseOutilsManage',
        'getTabCmdCb',
        {},
        function(data) {
          tabIdCmd = data.response.TabCmdCb;
          compteur = 0;
          genererPdfCmd(compteur);
        },
        function(response) {
          $timeout(function(){appFunctions.retourComposant();}, 3000);
        }
      );
      
    };
    
    var genererPdfCmd = function(pCompteur){
      
      appFunctions.post('fseOutilsManage',
        'genPdfCmdCb',
        {id_cmd_min: tabIdCmd[pCompteur].idmin, id_cmd_max: tabIdCmd[pCompteur].idmax},
        function(data) {
          if(data.response.pdfBase64){
            pCompteur ++;
            var nomPdf = "commandeFSE_" + pCompteur + "_" + tabIdCmd.length + ".pdf";
            appFunctions.saveBase64(data.response.pdfBase64, nomPdf);
            
            if(pCompteur <= tabIdCmd.length){
              genererPdfCmd(pCompteur);
            }
          }
          data.response.pdfBase64 = null;
        },
        function(response) {
        }
      );
      
      
    };
    
  }
]);


