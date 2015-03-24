
var app = angular.module("MetaChecker", []);

app.config(function($locationProvider) {
    $locationProvider.html5Mode(true);
});

app.controller("MetaCheckerController", function($scope, $http, $location, metaHelper) {
    $scope.url = "";
    $scope.showResults = false;
    $scope.showInfo = false;
    $scope.toggleInfo = function(){
        $scope.showInfo = !$scope.showInfo;
    }
         
        
    $scope.submit = function(){
        $location.search({url: $scope.url});
        if ($scope.url.length > 0) {
            $scope.message = 'Loading...';
            $http.get('./api/index.php?url='+$scope.url).success(function(data){
                
                if (data.success === false) {
                    $scope.message = data.general_message;
                    $scope.showResults = false;
                    return;
                }
                
                $scope.currentUrl = $scope.url;
                $scope.message = '';
                $scope.showResults = true;
                $scope.d = processData(data);
                $scope.groups = getGroups(data.details);
                
    
                function getGroups(data){
                    var groups = [];
                    for (var i = 0; i < data.length; i++) {
                        if ((data[i].group) && (groups.indexOf(data[i].group) === -1)) {
                            groups.push(data[i].group);
                        }
                    }
                    return groups;
                }
                
                function processData(d) {
                    var data = d.details;
                    for (var i = 0; i < data.length; i++) {
                        if (!data[i].group) {
                            data[i].group = 'other';
                        }
                        
                    }
                    d.details = data;
                    return d;
                }
                                
                
            }).error(function(data){
                $scope.message = 'The specified URL is invalid, or that page does not exist.';
            })
            ;
            
        }
        return false;
    }
    
    if ($location.search().url) {
        $scope.url = $location.search().url;
        $scope.submit();
    }
    
    
    
    
    
});

app.factory('metaHelper', function(){
   var checkUrlParam = function(url){
       
   }
   return {
       checkUrlParam: checkUrlParam
   }
});





/**
 * Autofill event polyfill ##version:1.0.0##
 * (c) 2014 Google, Inc.
 * License: MIT
 */
(function(window) {
  var $ = window.jQuery || window.angular.element;
  var rootElement = window.document.documentElement,
    $rootElement = $(rootElement);

  addGlobalEventListener('change', markValue);
  addValueChangeByJsListener(markValue);

  $.prototype.checkAndTriggerAutoFillEvent = jqCheckAndTriggerAutoFillEvent;

  // Need to use blur and not change event
  // as Chrome does not fire change events in all cases an input is changed
  // (e.g. when starting to type and then finish the input by auto filling a username)
  addGlobalEventListener('blur', function(target) {
    // setTimeout needed for Chrome as it fills other
    // form fields a little later...
    window.setTimeout(function() {
      findParentForm(target).find('input').checkAndTriggerAutoFillEvent();
    }, 20);
  });

  window.document.addEventListener('DOMContentLoaded', function() {
    // The timeout is needed for Chrome as it auto fills
    // login forms some time after DOMContentLoaded!
    window.setTimeout(function() {
      $rootElement.find('input').checkAndTriggerAutoFillEvent();
    }, 200);
  }, false);

  return;

  // ----------

  function jqCheckAndTriggerAutoFillEvent() {
    var i, el;
    for (i=0; i<this.length; i++) {
      el = this[i];
      if (!valueMarked(el)) {
        markValue(el);
        triggerChangeEvent(el);
      }
    }
  }

  function valueMarked(el) {
    var val = el.value,
         $$currentValue = el.$$currentValue;
    if (!val && !$$currentValue) {
      return true;
    }
    return val === $$currentValue;
  }

  function markValue(el) {
    el.$$currentValue = el.value;
  }

  function addValueChangeByJsListener(listener) {
    var jq = window.jQuery || window.angular.element,
        jqProto = jq.prototype;
    var _val = jqProto.val;
    jqProto.val = function(newValue) {
      var res = _val.apply(this, arguments);
      if (arguments.length > 0) {
        forEach(this, function(el) {
          listener(el, newValue);
        });
      }
      return res;
    }
  }

  function addGlobalEventListener(eventName, listener) {
    // Use a capturing event listener so that
    // we also get the event when it's stopped!
    // Also, the blur event does not bubble.
    rootElement.addEventListener(eventName, onEvent, true);

    function onEvent(event) {
      var target = event.target;
      listener(target);
    }
  }

  function findParentForm(el) {
    while (el) {
      if (el.nodeName === 'FORM') {
        return $(el);
      }
      el = el.parentNode;
    }
    return $();
  }

  function forEach(arr, listener) {
    if (arr.forEach) {
      return arr.forEach(listener);
    }
    var i;
    for (i=0; i<arr.length; i++) {
      listener(arr[i]);
    }
  }

  function triggerChangeEvent(element) {
    var doc = window.document;
    var event = doc.createEvent("HTMLEvents");
    event.initEvent("change", true, true);
    element.dispatchEvent(event);
  }

})(window);