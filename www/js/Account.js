App.Account = function(){
	//setup validation, see form for class names to use for valid / invlide.
	//trigger ajax check on change.
	/*********
	* Private
	**********/
	var self = this;
	function validateEmail(email) {
	  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	  return re.test(email);
	}

	function checkPassword(e){
		var check = true;
		var val1 = $("#newpassword").val();
		var val2 = $("#passwordVerify").val();
		var message = "Password appears to be good, But it's probably bad still.";

		function update(){
			$("#passwordHelp").removeClass("text-muted");//this has to be removed for the css to work.
			if (check){
				$("#newpassword").addClass("is-valid");
				$("#newpassword").removeClass("is-invalid");
				$("#passwordVerify").addClass("is-valid");
				$("#passwordVerify").removeClass("is-invalid");
				$("#passwordHelp").addClass("is-valid");
				$("#passwordHelp").removeClass("is-invalid");
			} else {
				$("#newpassword").addClass("is-invalid");
				$("#newpassword").removeClass("is-valid");
				$("#passwordVerify").addClass("is-invalid");
				$("#passwordVerify").removeClass("is-valid");
				$("#passwordHelp").addClass("is-invalid");
				$("#passwordHelp").removeClass("is-valid");
			}
			$("#passwordHelp").html(message);
		}

		if (val1 != val2){
			check = false;
			message = "Passwords don't match.";
		}
		if (val1.length < 8 ){
			check = false;
			message = "Password is too short.";
		}
		update();
	}

	function submitAJAX(e){
		var message = "";
		var check = false;
		$.ajax({
			url: "http://2huchat.studiojaw.com/api?c=users&a=update",
			method:"POST",
			dataType:"json",
			data:{
				oldpassword:$("#oldpassword").val(),
				newpassword:$("#newpassword").val(),
				passwordverify:$("#passwordVerify").val(),
			},
			success:function(res, status, xhr){
				if (res.success){
					$("#updateHelp").removeClass("is-invalid");
					$("#updateHelp").addClass("is-valid");
					$("#updateHelp").html("Saved!");
				} else {
					checkPassword();
					messages = "Could not save";
					if (res.errors){
						for ( i in res.errors ){
							messages += "<br>"+res.errors[i];
						}
					}
					$("#updateHelp").addClass("is-invalid");
					$("#updateHelp").removeClass("is-valid");
					$("#updateHelp").html(messages);
				}
			}
		})
		e.preventDefault();
	}

  function openFile(e){
    e.preventDefault();
    $("#avatarFile").trigger("click");
    return false;
  }

  function uploadAvatar(e){
    e.preventDefault();
    if ( $("#avatarFile")[0].files ){
      var file = $("#avatarFile")[0].files[0];
      var t = file.type;
      if ( t == "image/jpeg" || t == "image/png" ) {
        var img = new Image();
        img.src = window.URL.createObjectURL( file );

        img.onload = function(){
            var w = img.naturalWidth;
            var h = img.naturalHeight;
            var s = img.size;

            window.URL.revokeObjectURL(img.src);
            if ( h > 800 || w > 800 || s > 1048576 ){
                $("#avatarHelp").removeClass("text-muted");
                $("#avatarHelp").addClass("is-invalid");
            } else {
                $("#avatar").submit();
            }
        }
      }
    }
    return false;
  }

	function init(){
		$("#newpassword").on("keyup", checkPassword );
		$("#passwordVerify").on("keyup", checkPassword );
		$("#regform").on("submit", submitAJAX );
    //avatar
    $("#fileButton").on("click",openFile)
    $("#avatarFile").on("change",uploadAvatar)
	}
	/*********
	* Public
	**********/
	self.ajax = null;
	init();
}
