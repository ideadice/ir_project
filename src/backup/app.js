
var myApp = angular.module('myApp', []);


//Angular Controllers

myApp.controller('registration', ['$scope', function ($scope) {

	  $scope.onlyLetters = /^[a-zA-Z]+$/;
	    $scope.onlyNumbers = /^[0-9]*$/;

	
}]);


myApp.directive('tooltip', function() {
    return {
        restrict: 'A',
        transclude: true,
        template: '<div ng-transclude></div>' +
            '<div id="divPopup" ng-show="isShown">' +
            '<div class="floatLeft">' +
            '<img src="images/tooltipArrow.png" />' +
            '</div>' +
            '<div class="floatLeft margin3">' +
            '<span>' +
            'I am the Hover Popup' +
            '</span>' +
            '</div>' +
            '</div>'
    }
})