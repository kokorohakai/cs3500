function App(){
	/*********
	* Private
	**********/
	var self = this;

	function init(){
		//get user information.
		if (window.Vars){
			self.user = Vars.user;
			self.userid = Vars.userid;
		}
		//get the current user from the url
		var pageuser = window.location.pathname.substr(1);
		pageuser = pageuser.replace(/[- .\/\\\?]/,"_",pageuser);
		self.pageuser = pageuser;
		//get the request variables.
		var params = window.location.search.substr(1).split("&");
		for ( i in params ){
			var r = params[i].split("=");
			self.request[r[0]] = r[1];
		}
		//connect all the modules (usually only one) included by the pages.
		self.modules = {};
		
		for ( module in App){
			self.modules[module] = new App[module](self);
		}
	}
	
	/*********
	* Public
	**********/
	self.pageuser = "";
	self.user = "";
	self.userid = "";
	self.request = {};

	init();
}

$(function(){
	new App();
})
