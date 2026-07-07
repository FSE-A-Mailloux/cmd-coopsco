
/**
 * Déclaration de l'application tonyryuApp
 */
angular.module('tonyryuApp', [
  // Dépendances du "module"
  'ngRoute',
  'loginMod',
  'menuMod',
  'servicesMod',
  'vcRecaptcha',
  'smart-table',
  'ui.codemirror',
  'chart.js'
])
.config(function($locationProvider) {
  $locationProvider.html5Mode({
    enabled: true,
    requireBase: false
  });
})
.config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.timeout = 60000;
}])
.controller('appCtrl', ['$scope', 'dataStorage', 'appFunctions',
  function ($scope, dataStorage, appFunctions) {
    // variables
    $scope.rowCollection = [];
    $scope.displayedCollection = [];
    $scope.selectedRow = {};

    // fonctions
    $scope.getDatas = function() {
      return dataStorage;
    };

    $scope.getUrlComponent = function(){
      return appFunctions.getUrlComponent();
    };

    $scope.clickLogo = function(){
      appFunctions.loadComponent(dataStorage.appDatas.componentDefault);
    };

    $scope.isArray = angular.isArray;
  }
])
.run(function(appFunctions, $location, modalWindow) {
  var action = appFunctions.getUrlParams($location.url(), 'action');
  var contexte = appFunctions.getUrlParams($location.url(), 'contexte');
  var id = appFunctions.getUrlParams($location.url(), 'id');
  
  if(action === 'validLink'){
    appFunctions.post('login',
      'validLink',
      {
        id: id
      },
      function(data) {
        var modalOptions = {
          closeButtonText: '',
          actionButtonText: 'Ok',
          headerText: 'Activation terminée',
          bodyText: 'L\'activation de votre compte est maintenant terminée.'
        };
        modalWindow.showModal({}, modalOptions);

        appFunctions.connexion(data.response);
      },
      function(response){
        appFunctions.loadComponent('default');
      }
    );
  }else if(action === 'recupPass'){
    appFunctions.loadComponent('changePass', {idCode:id});
  }else if(action === 'desabo'){
    appFunctions.loadComponent('desabo', {idCode:id});
  }else if(action === 'paiementcb'){
    appFunctions.loadComponent('paiementcb', {contexte:contexte});
  }else{
    // initialisation
    appFunctions.loadComponent('default');
  }
})
.directive("myInclude", ['dataStorage', '$compile',function(dataStorage, $compile) {
  return {
    restrict: 'CAE',
    scope: {
      src: '=',
      myInclude: '='
    },
    transclude:true,
    link: function(scope, iElement, iAttrs, controller) {
      scope.$on("$includeContentError", function(event, args){
        dataStorage.appDatas.erreur = 'Composant introuvable [' + args + ']';
        dataStorage.appDatas.menu = [];
      });
      scope.$on("$includeContentLoaded", function(event, args){

      });
    },
    template: "<div ng-include='myInclude||src'></div>"
  };
}])
.directive("rcSubmit", ['$parse', function ($parse) {
  return {
    restrict: 'A',
    require: ['rcSubmit', '?form'],
    controller: ['$scope', function ($scope) {
      this.attempted = false;

      var formController = null;

      this.setAttempted = function() {
        this.attempted = true;
      };

      this.setFormController = function(controller) {
        formController = controller;
      };

      this.needsAttention = function (fieldModelController) {
        if (!formController) return false;

        if (fieldModelController) {
          return fieldModelController.$invalid && (fieldModelController.$dirty || this.attempted);
        } else {
          return formController && formController.$invalid && (formController.$dirty || this.attempted);
        }
      };
    }],
    compile: function(cElement, cAttributes, transclude) {
      return {
        pre: function(scope, formElement, attributes, controllers) {

          var submitController = controllers[0];
          var formController = (controllers.length > 1) ? controllers[1] : null;

          submitController.setFormController(formController);

          scope.rc = scope.rc || {};
          scope.rc[attributes.name] = submitController;
        },
        post: function(scope, formElement, attributes, controllers) {

          var submitController = controllers[0];
          var formController = (controllers.length > 1) ? controllers[1] : null;
          var fn = $parse(attributes.rcSubmit);

          formElement.bind('submit', function (event) {
            submitController.setAttempted();
            if (!scope.$$phase) scope.$apply();

            if (!formController.$valid) return false;

            scope.$apply(function() {
              fn(scope, {$event:event});
            });
          });
        }
      };
    }
  };
}])
.directive('validicon', function() {
  return {
    require: 'ngModel',
    link: function(scope, elm, attrs, ctrl) {
      ctrl.$validators.validicon = function(modelValue, viewValue) {
        var regExpGlyphicon = /^^glyphicon glyphicon-(\S*)$/;
        var regExpFa = /^fa fa-(.*)$/;
        if (ctrl.$isEmpty(modelValue)) {
          // consider empty models to be valid
          return true;
        }
        
        if (regExpGlyphicon.test(viewValue)) {
          // it is valid
          return true;
        }else if(regExpFa.test(viewValue)) {
          return true;
        }

        // it is invalid
        return false;
      };
    }
  };
})
.directive("searshfield", ['modalWindow', '$ocLazyLoad', 'appFunctions', 'dataStorage', '$templateCache', "$http", function(modalWindow, $ocLazyLoad, appFunctions, dataStorage, $templateCache, $http){
  return {
    restrict: 'EA',
    replace: true,
    
    scope: {
      ngModel: '=ngModel',
      required: '@',
      ngMaxlength : '=ngMaxlength',
      ngMinlength : '=ngMinlength',
      ngStyle: '=ngStyle',
      name: '@name',
      ngValue: '=ngValue',
      listId: '@id'
    },
    template: '<div class="input-group" style="width: 100%;">\n'+
//'  <input type="text" ng-style="ngStyle" class="form-control" name="name" ng-value="ngValue" ng-maxlength="ngMaxlength" ng-minlength="ngMinlength" ng-required="required" readonly>\n'+
'  <input list="datalist{{::listId}}" type="text" ng-style="ngStyle" class="form-control" name="{{::name}}" ng-value="ngValue" ng-maxlength="ngMaxlength" ng-minlength="ngMinlength" ng-readonly="testReadOnly()" ng-model="SelectDataId" ng-change="rechercherElement(SelectDataId)">\n'+
'  <datalist id="datalist{{::listId}}" autocomplete="off">\n'+
'    <option ng-repeat="rowData in ListeDonnees" value="{{rowData.Id}}"></option>\n'+
//'    <select style="display: none;" name="datalist{{::listId}}Select" ng-model="SelectDataId">\n'+
//'      <option ng-repeat="rowData in ListeDonnees" value="{{rowData.Id}}">{{rowData.Valeur}}</option>\n'+
//'    </select>\n'+
'  </datalist>\n'+
'  <input type="hidden" ng-model="ngModel">\n'+
'  <span class="input-group-btn">\n'+
'    <button class="btn btn-secondary" type="button" ng-click="searsh()"><span class="glyphicon glyphicon-search"></span></button>\n'+
'  </span>\n'+
'</div>',
    link: function(scope, element, attrs){
      
      scope.ListeDonnees = [];
      scope.SelectDataId = '';
      
      scope.testReadOnly = function(){
        return (scope.ListeDonnees.length === 0);
      };
      
      scope.rechercherElement = function(idElem){
        var elemTrouve = false;
        for(var i = 0; i < scope.ListeDonnees.length; i++){
          if(scope.ListeDonnees[i].Id === idElem) {
            scope.ngModel = scope.ListeDonnees[i].Code;
            scope.ngValue = scope.ListeDonnees[i].Valeur;
            elemTrouve = true;
            break;
          }else if(scope.ListeDonnees[i].Valeur === idElem && scope.ngModel === scope.ListeDonnees[i].Code){
            scope.ngModel = scope.ListeDonnees[i].Code;
            scope.ngValue = scope.ListeDonnees[i].Valeur;
            elemTrouve = true;
            break;
          }
        }
        if(!elemTrouve){
          scope.ngModel = '';
          scope.ngValue = '';
        }
      };

      scope.searsh = function searsh(){
        // charger le fichier js dynamiquement du composant utilisé
        $ocLazyLoad.load('app/components/' + attrs.sfCom+'/'+attrs.sfCom+'.js').then(function() {
          // Fichier JS chargé, appel au service getComponent          
          appFunctions.post('getComponent', '', {cd_com:attrs.sfCom}, function(data) {
            // une fois le composant js chargé, récupérer le HTML
            var fichierHtml = 'app/components/' + attrs.sfCom+'/'+attrs.sfCom+'.html';

            $http.get(fichierHtml).then(function(response) {
              // activer le mode de liste de recherche
              dataStorage.appDatas.searsh = true;          

              var template = response.data;

              //var template = $templateCache.get('app/components/' + attrs.sfCom+'/'+attrs.sfCom+'.html');
              template += "\n"+
"<div class=\"modal-footer\">\n"+
"  <button type=\"button\" class=\"btn\" \n"+
"          data-ng-click=\"modalOptions.close()\" \n"+
"          ng-show=\"modalOptions.closeButtonText !== ''\">{{modalOptions.closeButtonText}}</button>\n"+
"  <button class=\"btn btn-primary\" \n"+
"          data-ng-click=\"modalOptions.choice();\" \n"+
"          ng-show=\"modalOptions.actionButtonText !== ''\" >{{modalOptions.actionButtonText}}</button>\n"+
"</div>\n";

              var modalDefaults = {
                size: "lg", 
                backdrop: true,
                keyboard: true,
                modalFade: true,
                template: template,
                searshList: true
              };
              var modalOptions = {
                closeButtonText: 'Retour',
                actionButtonText: 'Ok',
                headerText: 'Recherche',
                bodyText: 'Test de popup de recherche'
              };
              modalWindow.showModal(modalDefaults, modalOptions).then(function (result){
                dataStorage.appDatas.searsh = false;
                scope.ngModel = result[attrs.sfCode];
                scope.ngValue = result[attrs.sfValue];
              }, function(resut){
                dataStorage.appDatas.searsh = false;
              }, function(resut){
                dataStorage.appDatas.searsh = false;
              }).catch(angular.noop);



            });

 
          });
        }, function(e) {
          // Erreur de chargement de Js
          appFunctions.post('getComponent', '', {cd_com:attrs.sfCom}, function(data) {});
        });

      };
      
      // Charger directement la liste des éléments via la requête
      if(attrs.sfRequest){
        appFunctions.post('execRequest',
          attrs.sfRequest,
          {},
          function(data) {
            for(var i = 0; i < data.response.result.length; i++){
              scope.ListeDonnees.push({
                Valeur: data.response.result[i][attrs.sfValue],
                Code: String(data.response.result[i][attrs.sfCode]),
                Id: data.response.result[i][attrs.sfValue] + ' |' + String(data.response.result[i][attrs.sfCode])
              });
            }
          },
          function(response) {

          }
        );
        
      }
      
    }
  };
}])
.directive('compareTo', function() {
  return {
    require: "ngModel",
    scope: {
      otherModelValue: "=compareTo"
    },
    link: function(scope, element, attributes, ngModel) {

      ngModel.$validators.compareTo = function(modelValue) {
        return modelValue == scope.otherModelValue;
      };

      scope.$watch("otherModelValue", function() {
        ngModel.$validate();
      });
    }
  };
})
.directive('bindCompiledHtml', function ($compile) {
    // return directive defination object
    return {
        scope: {
            bindCompiledHtml: '='
        },
        link: function ($scope, $element) {
            var childScope;
            $scope.$watch('bindCompiledHtml', function (bindCompiledHtml) {
                if (childScope) {
                    childScope.$destroy();
                }
                if (bindCompiledHtml) {
                    var linkFunc = $compile(bindCompiledHtml);
                    childScope = $scope.$parent.$new();
                    linkFunc(childScope, function (compElement) {
                        $element.html('');
                        $element.append(compElement);
                    });
                }
            })
        }
    };
})
.directive('stDefaultSelection', function() {
  return {
    require: 'stTable',
    restrict: 'A',
    scope: {
      selection: '=stDefaultSelection',
    },
    link: function link(scope, element, attrs, controller) {
      var pagination = null,
        hasLoaded = false,
        selectionMode = 'single';

      scope.$watch(function() {

        pagination = controller.tableState().pagination;

        if (pagination != null && pagination.number != null && hasLoaded === false) {
         
          if(!scope.selection){
            scope.selection = {code:"", value:""}
          }else{
            if(!scope.selection.code)
              scope.selection.code = "";
            if(!scope.selection.value)
              scope.selection.value = "";
          }

          // Modifier sélection
          var collection = controller.getFilteredCollection(),
            selection = collection.find( item => item[scope.selection.code] === scope.selection.value)

          var rows = controller.getFilteredCollection(),
            indexOfRow = rows.indexOf(selection),
            finalPage = Math.floor(indexOfRow / pagination.number); // starts at zero

          if (indexOfRow > -1) {
            controller.slice(finalPage * pagination.number, pagination.number);
            hasLoaded = true;
          }
          controller.select(selection, selectionMode);
        }
      }, true);
    }
  };
})
.filter('useFilter', function($filter) {
    return function() {
        var filterName = [].splice.call(arguments, 1, 1)[0];
        return $filter(filterName).apply(null, arguments);
    };
});


// Autres fonctions utiles
Array.prototype.move = function(from, to) {
  this.splice(to, 0, this.splice(from, 1)[0]);
};