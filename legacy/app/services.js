angular.module('servicesMod', [
  "oc.lazyLoad",
  'ui.bootstrap'
]).value('dataStorage', {
  appDatas: {
    promiseTimeout: null,
    erreur : "Message d'erreur",
    dataLoading: 0,
    token: '',
    compte_uti: '',
    id_uti: 0,
    menu:[],
    combos:{},
    componentDefault:'default',
    tabGroupe:[],
    currentComponent:"",
    poolComponents:[{nom:'default', params:{}}],
    fonctionsComp:{},
    searsh:false,
    searshGetRow:null,
    row:{},
    tabSavedSelectedRow:[]
  },
  params: {
    loginActivate: false,
    createAccountActivate: false,
    captchaPublicKey:''
  }

})
.service('modalWindow', [
  '$uibModal', 'dataStorage', 
  function ($uibModal, dataStorage) {

    var modalDefaults = {
      backdrop: true,
      keyboard: true,
      modalFade: true,
      template: "<div class=\"modal-header\">\n"+
"  <h3>{{modalOptions.headerText}}</h3>\n"+
"</div>\n"+
"<div class=\"modal-body\" style=\"text-align: center;\">\n"+
//"  <p class=\"angular-with-newlines\">{{modalOptions.bodyText}}</p>\n"+
"  <p bind-compiled-html=\"modalOptions.bodyText\"></p>\n"+
"</div>\n"+
"<div class=\"modal-footer\">\n"+
"  <button type=\"button\" class=\"btn\" \n"+
"          data-ng-click=\"modalOptions.close()\" \n"+
"          ng-show=\"modalOptions.closeButtonText !== ''\">{{modalOptions.closeButtonText}}</button>\n"+
"  <button class=\"btn btn-primary\" \n"+
"          data-ng-click=\"modalOptions.ok();\" \n"+
"          ng-show=\"modalOptions.actionButtonText !== ''\" >{{modalOptions.actionButtonText}}</button>\n"+
"</div>\n"
    };

    var modalOptions = {
      closeButtonText: 'Close',
      actionButtonText: 'OK',
      headerText: 'Proceed?',
      bodyText: 'Perform this action?'
    };

    this.showModal = function (customModalDefaults, customModalOptions) {
      if (!customModalDefaults)
        customModalDefaults = {};
      customModalDefaults.backdrop = 'static';
      return this.show(customModalDefaults, customModalOptions);
    };

    this.show = function (customModalDefaults, customModalOptions) {
      //Create temp objects to work with since we're in a singleton service
      var tempModalDefaults = {};
      var tempModalOptions = {};

      //Map angular-ui modal custom defaults to modal defaults defined in service
      angular.extend(tempModalDefaults, modalDefaults, customModalDefaults);
      
      if(tempModalDefaults.templateUrl){
        tempModalDefaults.template = "";
      }

      //Map modal.html $scope custom properties to defaults defined in service
      angular.extend(tempModalOptions, modalOptions, customModalOptions);

      // Ajouter les div au body
      tempModalOptions.bodyText = "<div>"+tempModalOptions.bodyText+"</div>";

      if (!tempModalDefaults.controller) {
        tempModalDefaults.controller = function ($scope, $uibModalInstance) {
          $scope.modalOptions = tempModalOptions;
          $scope.modalOptions.ok = function (result) {
            $uibModalInstance.close(result);
          };
          $scope.modalOptions.close = function (result) {
            $uibModalInstance.dismiss('cancel');
          };
          $scope.modalOptions.choice = function (result) {
            closeGetRow();
          };
          
          var closeGetRow = function(row){
            if(!row){
              row = dataStorage.appDatas.searshGetRow();
            }
            $uibModalInstance.close(row);
          };
          
          dataStorage.appDatas.searshCloseGetRow = closeGetRow;
          
        };
      }

      return $uibModal.open(tempModalDefaults).result;
  };
}])
.factory('appFunctions', ['dataStorage', '$http', '$timeout', '$ocLazyLoad', 'modalWindow', function(dataStorage, $http, $timeout, $ocLazyLoad, modalWindow) {
    
  var obj = {};
  obj.getUrlComponent = function() {
    var idxComp = dataStorage.appDatas.poolComponents.length;
    var nom =  dataStorage.appDatas.poolComponents[idxComp - 1].nom;
    return "app/components/" + nom + "/" + nom + ".html";
  };
  
  obj.changeComponent = function(pMenu, pComponent, pParams, pCombos){
    // si composant et celui par défaut
    if(pComponent === dataStorage.appDatas.componentDefault){
      dataStorage.appDatas.poolComponents = [{nom:pComponent, params:{}, combos:{}}];
    }
    if(pComponent !== dataStorage.appDatas.poolComponents[dataStorage.appDatas.poolComponents.length - 1].nom){
      pParams = pParams || {};
      pCombos = pCombos || {};
      dataStorage.appDatas.poolComponents.push({nom:pComponent, params:pParams, combos: pCombos});
    }else if(!!pParams){
      dataStorage.appDatas.poolComponents[dataStorage.appDatas.poolComponents.length - 1].params = pParams;
      dataStorage.appDatas.poolComponents[dataStorage.appDatas.poolComponents.length - 1].combos = pCombos;
    }
    dataStorage.appDatas.searsh = false;
    dataStorage.appDatas.menu = pMenu;
    //dataStorage.appDatas.combos = (pCombos || {});
    dataStorage.appDatas.currentComponent = pComponent;
  };
  
  obj.loadComponent = function(pComponent, pParams){
    $ocLazyLoad.load('app/components/' + pComponent+'/'+pComponent+'.js').then(function() {
      obj.post('getComponent', '', {cd_com:pComponent}, function(data) {
        // récupérer les paramètres applicatifs par défaut
        // pour chaque paramètre
        for (var key in data.response.params) {
          if (data.response.params.hasOwnProperty(key)) {
            dataStorage.params[key] = data.response.params[key];
          }
        }
        obj.changeComponent(data.response.menu, pComponent, pParams, data.response.combo);
      });
    }, function(e) {
      // Erreur de chargement de Js
      //console.log('fichier app/components/' + pComponent+'/'+pComponent+".js n'existe pas");
      
      obj.post('getComponent', '', {cd_com:pComponent}, function(data) {
        // récupérer les paramètres applicatifs par défaut
        // pour chaque paramètre
        for (var key in data.response.params) {
          if (data.response.params.hasOwnProperty(key)) {
            dataStorage.params[key] = data.response.params[key];
          }
        }
        obj.changeComponent(data.response.menu, pComponent, pParams, data.response.combo);
      });
    });

  };
  
  obj.retourComposant = function(pParams){
    var idxComp = dataStorage.appDatas.poolComponents.length;
    if(idxComp === 1){
      obj.loadComponent(dataStorage.appDatas.componentDefault);
    }else{
      var comp = dataStorage.appDatas.poolComponents[idxComp - 2].nom;
      if(pParams){
        dataStorage.appDatas.poolComponents[idxComp - 2].params = pParams;
      }
      dataStorage.appDatas.poolComponents.pop();
      obj.loadComponent(comp);
    }
  };
  
  obj.retourAuComposant = function(pNomComposant, pParams){
    var idxComp = dataStorage.appDatas.poolComponents.length;
    if(idxComp === 1){
      obj.loadComponent(dataStorage.appDatas.componentDefault);
    }else{
      do {
        var comp = dataStorage.appDatas.poolComponents[idxComp - 2].nom;
        dataStorage.appDatas.poolComponents.pop();
        idxComp = idxComp - 1;
      } while(idxComp > 1 && comp !== pNomComposant);
      
      if(idxComp === 1){
        obj.loadComponent(dataStorage.appDatas.componentDefault);
      }else{
        if(pParams){
          dataStorage.appDatas.poolComponents[idxComp - 1].params = pParams;
        }
        obj.loadComponent(comp);
      }
    }
  };
  
  obj.getParametresComposant = function(){
    var idxComp = dataStorage.appDatas.poolComponents.length;
    return dataStorage.appDatas.poolComponents[idxComp - 1].params;
  };
  
  obj.getCombosComposant = function(){
    var idxComp = dataStorage.appDatas.poolComponents.length;
    return dataStorage.appDatas.poolComponents[idxComp - 1].combos;
  };
  
  obj.connexion = function(pParams){
    // initialiser le token
    dataStorage.appDatas.token = pParams.token;
    dataStorage.appDatas.compte_uti = pParams.compte_uti;
    dataStorage.appDatas.id_uti = pParams.id_uti;
    dataStorage.appDatas.componentDefault = pParams.cd_com;
    dataStorage.appDatas.tabGroupe = pParams.tabGroupe;

    // appeler le composant principale de l'utilisateur
    obj.loadComponent(pParams.cd_com);
  };
  
  obj.deconnexion = function(){
    if(dataStorage.appDatas.promiseTimeout){
      $timeout.cancel(dataStorage.appDatas.promiseTimeout);
      dataStorage.appDatas.promiseTimeout = null;
    }
    dataStorage.appDatas.componentDefault = 'default';
    dataStorage.appDatas.token = '';
    dataStorage.appDatas.compte_uti = '';
    dataStorage.appDatas.id_uti = 0;
    obj.loadComponent(dataStorage.appDatas.componentDefault);
  };
  
  obj.post = function(action, contexte, params, callbackSuccess, callbackError) {
    dataStorage.appDatas.dataLoading = 1;
    var req = {
      method: 'POST',
      url: 'ws/wsApp.php',
      headers: {
        'Content-Type': 'application/json'
      },
      data: {
        token:dataStorage.appDatas.token,
        action:action,
        context:contexte,
        params:params
      }
    };
    $http(req).then(function(response) {
      dataStorage.appDatas.dataLoading = 0;
      dataStorage.appDatas.erreur = "";
      if(response.data.statusCode === 'Error'){
        dataStorage.appDatas.erreur = "Erreur appel WS '"+action+"' : " + response.data.statusMessage;
        if(callbackError)
          callbackError(response);
      }else if(response.data.statusCode === 'ErrorTokenExpirate'){
        dataStorage.appDatas.erreur = "Session expirée";
        dataStorage.appDatas.promiseTimeout = $timeout(function(){obj.deconnexion();}, 5000);
        if(callbackError)
          callbackError(response);
      }else if(response.data.statusCode === 'ErrorSecurity'){
        // TODO afficher fenêtre modal de relancement application
        dataStorage.appDatas.erreur = "Erreur appel WS '"+action+"' : " + response.data.statusMessage;
        dataStorage.appDatas.promiseTimeout = $timeout(function(){obj.deconnexion();}, 5000);
        if(callbackError)
          callbackError(response);
      }else if(response.data.statusCode === 'ErrorFonctionnal'){
        dataStorage.appDatas.erreur = response.data.statusMessage;
        if(callbackError)
          callbackError(response);
      }/*else if(data.Status === 'Update')
        $location.path("/update");*/
      else if(response.data.statusCode !== 'OK'){
        dataStorage.appDatas.erreur = "Erreur appel WS '"+action+"' : Le status retourné est inconnu : "+response.data;
        if(callbackError)
          callbackError(response);
      }
      else if(callbackSuccess){
        dataStorage.appDatas.erreur = "";
        callbackSuccess(response.data, response.status);
      }

    }, function(error) {
      
      var fausseErreur = false;
      
      dataStorage.appDatas.dataLoading = 0;
      dataStorage.appDatas.erreur = "";
      
      if(error.data.statusCode === undefined){
        if(error.data.statusCode === 'OK'){
          fausseErreur = true;
        }
      }
      if(fausseErreur){
        if(callbackSuccess){
          callbackSuccess(error.data, error.status);
        }
      }else{
        dataStorage.appDatas.erreur = "Erreur appel WS '"+action+"' : Code de retour HTTP : " + error.status + " " + error.statusText ;
      }
      //if(callbackError)
      //  callbackError(error, status);
    });
  };
  
  obj.ajouterFonctionsComp = function(pComp, pListeFonctions){
    //if(!dataStorage.appDatas.fonctionsComp.hasOwnProperty(pComp)){
      dataStorage.appDatas.fonctionsComp[pComp] = {};
      for (var key in pListeFonctions) {
        dataStorage.appDatas.fonctionsComp[pComp][key] = pListeFonctions[key];
      }
    //}
  };
  
  obj.executerFonctionComp = function(pFonction){
    var idxComp = dataStorage.appDatas.poolComponents.length;
    var nomComp =  dataStorage.appDatas.poolComponents[idxComp - 1].nom;
    
    if(dataStorage.appDatas.fonctionsComp.hasOwnProperty(nomComp)){
      if(dataStorage.appDatas.fonctionsComp[nomComp].hasOwnProperty(pFonction)){
        dataStorage.appDatas.fonctionsComp[nomComp][pFonction]();
      }
    }
  };

  obj.getUrlParams = function(pUrl, pParam){
    // trouver le ?
    var param = "";
    var idx = pUrl.indexOf("?");
    if(idx > 0){
      param = pUrl.substring(idx+1);
      var tabParam = param.split('&');
      for(var i = 0; i < tabParam.length; i++){
        if(tabParam[i].split('=')[0] === pParam){
          return tabParam[i].split('=')[1];
        }
      }
    }
    return "";
  };
  
  obj.getRowSelectTable = function(collection, msg){
    for(var i = 0; i < collection.length; i++){
      if(collection[i].isSelected){
        return collection[i];
      }
    }
    if(msg){
      var modalOptions = {
        closeButtonText: '',
        actionButtonText: 'Ok',
        headerText: 'Information',
        bodyText: msg
      };
      modalWindow.showModal({}, modalOptions).catch(angular.noop);
    }
    return null;
  };
  
  obj.saveByteArray = function(blob, fileName) {
    var url = window.URL.createObjectURL(blob);

    var anchorElem = document.createElement("a");
    anchorElem.style = "display: none";
    anchorElem.href = url;
    anchorElem.download = fileName;

    document.body.appendChild(anchorElem);
    anchorElem.click();

    document.body.removeChild(anchorElem);

    // On Edge, revokeObjectURL should be called only after
    // a.click() has completed, atleast on EdgeHTML 15.15048
    setTimeout(function() {
        window.URL.revokeObjectURL(url);
    }, 1000);
  };
  
  
  obj.saveBase64 = function(pBase64, pFileName){
    var blob1 = new Blob([Base64Binary.decode(pBase64)], {type: "application/octet-stream"});
    obj.saveByteArray(blob1, pFileName);
  };
  
  
  obj.formatPhoneNumber = function(pNumBrut){
    // lire tous les caractères en partant de la fin
    var result = "";
    var pos = 0;
    for (var i = 0; i < pNumBrut.length; i++) {
      // vérifier que c'est un numérique
      var j = (pNumBrut.length - 1) - i;
      if(pNumBrut.charCodeAt(j) >= 48 && pNumBrut.charCodeAt(j) <= 57){
        result = pNumBrut.charAt(j) + result;
        pos ++;
        if(pos === 2 && j !== 0){
          pos = 0;
          result = " " + result;
        }
      }
    }
    return result;
  };
  
  obj.saveSelectedRow = function(pCode, pValue, pComponent){
    if(!pComponent){
      pComponent = dataStorage.appDatas.currentComponent;
    }
    dataStorage.appDatas.tabSavedSelectedRow[pComponent] = {code:pCode, value:pValue};
  };
  
  obj.loadSelectedRow = function(pComponent){
    if(!pComponent){
      pComponent = dataStorage.appDatas.currentComponent;
    }
    if(!dataStorage.appDatas.tabSavedSelectedRow[pComponent]){
      return {};
    }
    return dataStorage.appDatas.tabSavedSelectedRow[pComponent];
  };
  
  return obj;

}]);

