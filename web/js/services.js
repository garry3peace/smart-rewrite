app.service('ApiService',function ($http, $q){
	
	this.getUrl = function(){
		return Params.apiBaseUrl;
	};
	
	this.rewrite = function(data){
		var url = this.getUrl()+"/index";
		
		return $http.post(url,data)
			.then(function(response) {
				if(response.status===200){
					return response.data;
				}
			}, function(response) {
				// something went wrong
				return $q.reject(response);
			});
	};
	
});

app.service("ErrorService", function(){
	this.show = function(message){
		alert(message);
	};
});

app.service("SpintaxService", function(){
	this.unspin = function(content){
		return spintax.unspin(content);
	};
});
