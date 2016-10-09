var app = angular.module('app', []);

app.run( function run($http){
    $http.defaults.headers.post['X-CSRF-Token'] = $('meta[name="csrf-token"]').attr("content");
    //$http.defaults.headers.post['content-type'] = 'application/json';
    //$http.defaults.headers.post['Accept'] = 'application/json';
});

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