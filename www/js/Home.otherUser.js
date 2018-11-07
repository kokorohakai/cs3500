App.HomeOtherUser = function( parent ){
  var self = this;

	function getMessages(){
		if ($("#messages")){
			$.ajax({
				url: "http://2huchat.studiojaw.com/api?c=messages&a=getusersmessages",
				method:"POST",
				dataType:"json",
				data: {
					username: parent.pageuser,
					startat:0,
				},
				success:function(res, status, xhr){
					for ( var i in res ){
						parent.modules.Home.addMessage(res[i]);
					}
				}
			})
		}
	}

	function init(){
		getMessages();
	}

	/*********
	* Public
	**********/
	init();
}
