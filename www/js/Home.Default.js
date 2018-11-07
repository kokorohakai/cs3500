App.HomeDefault = function(parent){
	var self = this;

	function getPopularMessages(){
		$.ajax({
			url: "http://2huchat.studiojaw.com/api?c=pubmessages&a=getpopular",
			method:"POST",
			dataType:"json",
			data: {
				startat:0,
			},
			success:function(res, status, xhr){
				for ( var i in res ){
					parent.modules.Home.addMessage(res[i]);
				}
			}
		});
	}

	function init(){
		getPopularMessages();
	}

	/*********
	* Public
	**********/
	init();
}