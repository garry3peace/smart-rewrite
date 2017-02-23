app.service('ApiService',function ($http, $q){
	/**
	 * Fungsi GET pada API
	 * @param string url
	 * @returns promise
	 */
	this.get = function (url) {
		url = this.getUrl() + url;
		return $http.get(url, {timeout: 10000})
				.then(function (response) {
					if (response.status === 200) {
						return response.data;
					}
				}, function (response) {

					// something went wrong
					return $q.reject(response);
				});
	};


	/**
	 * Opsi konfigurasi
	 * @param {type} type
	 * @returns Promise
	 */
	this.httpConfig = function (type) {
		//Tipe default adalah x-www-form-urlencoded. Ini tipe paling umum
		//Digunakan murni untuk teks. Tapi bisa juga untuk upload file, dengan catatan
		//dalam format base64.
		if (type === undefined) {
			type = 'x-www-form-urlencoded';
			//Kalau semuanya bukan, maka kita kirim yang default
			return {
				headers: {'Content-Type': 'application/x-www-form-urlencoded'},
				transformRequest: function (obj) {
					var str = [];
					for (var p in obj)
						str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
					return str.join("&");
				}
			};
		}

		//Form-data kalau mau upload file dan akses via $_FILES (PHP biasa)
		//atau UploadFileInstance (dalam Yii2)
		if (type === 'form-data') {
			return {
				headers: {'Content-Type': 'multipart/form-data'},
			};
		}

		return {
			headers: {'Content-Type': type},
		};
	};


	/**
	 * Menembak POST ke server
	 * @param string url target
	 * @param mixed data
	 * @param string type yang dikirim "form-data" atau "x-www-form-urlencoded"
	 * @returns promise
	 */
	this.post = function (url, data, type) {
		return $http.post(url, data, this.httpConfig(type))
				.then(function (response) {
					if(response.status===200){
						return response.data;
					}
				}, function (response) {
					// something went wrong
					return $q.reject(response);
				});
	};

	/**
	 * Fungsi untuk menambahkan parameter access token ke url 
	 * untuk ditembak ke API
	 */
	this.getAccessToken = function(){
		var access_token = Session.get(); 
		return '?access-token='+access_token;
	};

	/**
	 * Login ke sistem dan mereturn promise hasil dari login
	 * @param array username, password
	 * @returns promise
	 */
	this.login = function (data)
	{
		var apiUrl = '/user/login';
		return this.post(apiUrl, data);
	};
			
	this.getUrl = function(){
		return Params.apiBaseUrl;
	};
	
	this.rewrite = function(data){
		var url = this.getUrl()+"/default/index";
		return this.post(url,data, 'application/json');
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
