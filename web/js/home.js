var myAppModule = angular.module('app', []);

myAppModule.controller('SpintaxController',
	function ($scope){
		
		//Starting Init
		$scope.spintaxText		= window.spintaxText;
		$scope.sourceText		= window.sourceText;
		$scope.resultText		= spintax.unspin($scope.sourceText);
		//End of Init
		
		$scope.regenerate = function()
		{
			var content = $scope.spintaxText;
			var result = spintax.unspin(content);
			$scope.resultText= result;
		};
		
		
		
		
		
	});