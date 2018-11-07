App.MessageForm = function( parent ){
	/*************
	* Public
	*************/

	/*************
	* Private
	*************/
	var self = this;
	var limit = 200;
	var error = false;

	function checkLimit(e){
	    var val = $("#message").val();
	    if (val.length < 1 || val.length > limit){
			$("#messageHelp").removeClass("text-muted");
			$("#messageHelp").addClass("is-invalid");
			$("#messageSubmit").attr("disabled", true);
	    } else {
			$("#messageHelp").addClass("text-muted");
			$("#messageHelp").removeClass("is-invalid");
			$("#messageSubmit").attr("disabled", false);
	    }
	    $("#messageCount").html( limit - val.length);
	}

	function keyup(e){
		//something to consider doing later.
		if (e.shiftKey && e.which==50) { //@ character.
			//console.log(e.which);
			//console.log(e.shiftKey);
		}
		checkLimit(e);
	}

  	function sendMessage(e){
  		//only like this in case I need to do error correcting... but I don't think I do.
		$("messageForm").trigger("submit");
	}

	function openFile(e){
		e.preventDefault();
	    $("#mediaFile").trigger("click");
	    return false;
	}

	function setError( str ){
		$("#messageErrors").html(str);
	}

	function setMedia(e){
		e.preventDefault();
    	if ( $("#mediaFile")[0].files ){
      		var file = $("#mediaFile")[0].files[0];
      		if (file){
	      		if (
	      			file.type == "image/png" ||
	      			file.type == "image/jpeg" ||
	      			file.type == "audio/mp3" ||
	      			file.type == "audio/wav" ||
	      			file.type == "video/ogg" ||
	      			file.type == "video/mp4"
	      		){
	      			if ( file.size < 1048576 ){
	      				limit = 25;
	      				checkLimit(e);
	      				setError("");
	      			} else {
	      				limit = 200;
	      				checkLimit(e);
	      				$("#mediaFile").val("");
	      				setError("File too large to upload");
	      			}
	      		} else {
	      			limit = 200;
	      			checkLimit(e);
	      			$("#mediaFile").val("");
	      			setError("Cannot upload this file type");
	      		}
	      	} else { //file removed?
      			limit = 200;
      			checkLimit(e);	      		
	      	}
      	}
	}

	function togglePermission(e){
		$("#messagePermButton").empty()
		$("#messagePerm").val( $(e.target).attr("value") );
		$(e.target).clone().appendTo("#messagePermButton");
	}
	function init(){
    	$("#permMenu span").on("click",togglePermission);
		$("#message").on("keyup",keyup);
	    $("#messageForm").on("submit",sendMessage);
	    $("#mediaFileButton").on("click",openFile);
	    $("#mediaFile").on("change",setMedia);
	}

	function updateMessages( id ){
		$.ajax({
			url: "http://2huchat.studiojaw.com/api?c=messages&a=getmessage",
			dataType:"json",
			method:"POST",
			data:{
				id: id
			},
			success:function(res, status, xhr){
			    if (res[0]){
					parent.modules.Home.addMessage( res[0], false );
				}
			}
		});
	}

	init();

	/*************
	* Static
	*************/
	App.MessageForm.Complete = function( res ){
		if (res.errors){
			var message = "";
			for ( var i in res.errors){
				message += res.errors[i] + "<br>";
			}
			$("#messageErrors").html(message);
		} else if( res.success ) {
			$("#message").val("");
			$("#message").html("");//some browsers...
			$("#mediaFile").val("");
			checkLimit();
			updateMessages( res.messageid );
		} else {
			window.location.reload();
		}
	}

}