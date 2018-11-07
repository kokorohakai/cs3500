App.HomeNoUser = function( parent ){
	var self = this;

	function getMessages(){
		if (parent.request.tag){
			if ($("#messages")){
				$.ajax({
					url: "http://2huchat.studiojaw.com/api?c=messages&a=getbytag",
					method:"POST",
					dataType:"json",
					data:{
						startat:0,
						tag:parent.request.tag
					},
					success:function(res, status, xhr){
						for ( var i in res ){
							parent.modules.Home.addMessage(res[i]);
						}
					}
				})
			}
		} else {
			if ($("#messages")){
				$.ajax({
					url: "http://2huchat.studiojaw.com/api?c=messages&a=getfollowedmessages",
					method:"POST",
					dataType:"json",
					data:{
						startat:0
					},
					success:function(res, status, xhr){
						for ( var i in res ){
							parent.modules.Home.addMessage(res[i]);
						}
					}
				})
			}
		}
	}

	function init(){
		$("#myMessage").on("keyup",parent.modules.Home.checkLimit);
		$("#myPermMenu span").on("click",parent.modules.Home.togglePermission);
		getMessages();
	}

	/*********
	* Public
	**********/
	init();
}
