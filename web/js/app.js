var app = angular.module('app', []);

app.controller('SpintaxController',function ($scope, ApiService, SpintaxService, ErrorService){
		
	$scope.regenerate = function()
	{
		var content = $scope.Spin.spinTax;
		var result = SpintaxService.unspin(content);
		$scope.Spin.result = result;
	};

	$scope.rewrite = function()
	{
		var data = {
			"Spin":$scope.Spin,
			"Options":$scope.Options
		};
		
		promise = ApiService.rewrite(data);
		promise.then(function(result){
			$scope.Spin.spinTax = result;
			$scope.regenerate();
		},function(failure){
			ErrorService.show("Gagal menampilkan halaman");
		});
	};
});