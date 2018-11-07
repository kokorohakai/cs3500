App.HomeMyhome = function( parent ){
	var self = this;

	function getMessages(){
		$("#messages").html(""); //for now...
		$.ajax({
			url: "http://2huchat.studiojaw.com/api?c=messages&a=getmymessages",
			method:"POST",
			dataType:"json",
			data:{
				startat:0,
			},
			success:function(res, status, xhr){
				for ( var i in res ){
					parent.modules.Home.addMessage(res[i]);
				}
			}
		})
	}

	function init(){
		getMessages();
	}

	/*********
	* Public
	**********/
	init();
}
