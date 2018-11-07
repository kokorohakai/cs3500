App.Register = function(){
	//setup validation, see form for class names to use for valid / invlide.
	//trigger ajax check on change.
	/*********
	* Private
	**********/
	var self = this;
	function validateEmail(email) {
	  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
	  return re.test(email);
	}

	function validateUsername(username){
		var uname = username.match(/^[a-z0-9]*/i);
		return uname == username;
	}

	function checkEmail(e){
		var check = false;
		var val = $("#email").val();
		var message = "This is a good e-mail address, you must be proud.";
		
		function update(){
			$("#emailHelp").removeClass("text-muted");//this has to be removed for the css to work.
			if (check){
				$("#email").addClass("is-valid");
				$("#email").removeClass("is-invalid");
				$("#emailHelp").addClass("is-valid");
				$("#emailHelp").removeClass("is-invalid");
			} else {
				$("#email").addClass("is-invalid");
				$("#email").removeClass("is-valid");
				$("#emailHelp").addClass("is-invalid");
				$("#emailHelp").removeClass("is-valid");
			}
			$("#emailHelp").html(message);
		}

		if (validateEmail(val)){
			check = true;
			if (self.ajax) { self.ajax.abort(); } //abort the request if the previous hasn't completed.
			self.ajax = $.ajax({
				url: "http://2huchat.studiojaw.com/api?c=users&a=checkemail",
				method:"POST",
				dataType:"json",
				data:{
					email:val
				},
				success: function(res, status, xhr ){
					if ( res["success"] ){
						check = true;
						update();
					} else {
						check = false;
						message = "";
						if (res.errors){
							for (var i in res.errors){
								message += res.errors[i] + "\n";
							}
						} else {
							message = "Unkown Error";
						}
						update();
					}
				},
				failure: function(){
					check = false;
					message = "Unknown Error";
					update();
				}
			})
		} else {
			check = false;
			message = "Invalid E-mail Address";
			update();
		}
	}
	function checkUsername(e){
		var check = false;
		var val = $("#username").val();
		var message = "This is a good name, your parents must love you very much.";
		
		function update(){
			$("#usernameHelp").removeClass("text-muted");//this has to be removed for the css to work.
			if (check){
				$("#username").addClass("is-valid");
				$("#username").removeClass("is-invalid");
				$("#usernameHelp").addClass("is-valid");
				$("#usernameHelp").removeClass("is-invalid");
			} else {
				$("#username").addClass("is-invalid");
				$("#username").removeClass("is-valid");
				$("#usernameHelp").addClass("is-invalid");
				$("#usernameHelp").removeClass("is-valid");
			}
			$("#usernameHelp").html(message);
		}

		if ( validateUsername(val) ){
			if ( val.length > 0 ){
				check = true;
				if (self.ajax) { self.ajax.abort(); } //abort the request if the previous hasn't completed.
				self.ajax = $.ajax({
					url: "http://2huchat.studiojaw.com/api?c=users&a=checkusername",
					method:"POST",
					dataType:"json",
					data:{
						username:val
					},
					success: function(res, status, xhr ){
						if ( res["success"] ){
							check = true;
							update();
						} else {
							check = false;
							message = "";
							if (res.errors){
								for (var i in res.errors){
									message += res.errors[i] + "\n";
								}
							} else {
								message = "Unkown Error";
							}
							update();
						}
					},
					failure: function(){
						check = false;
						message = "Unknown Error";
						update();
					}
				})
			} else {
				check = false;
				message = "Username Too Short.";
				update();
			}
		} else {
			check = false;
			message = "Invalid Username, please use only characters a-z, 0-9";
			update();
		}
	}
	function checkPassword(e){
		var check = true;
		var val1 = $("#password").val();
		var val2 = $("#passwordVerify").val();
		var message = "Password appears to be good, But it's probably bad still.";

		function update(){
			$("#passwordHelp").removeClass("text-muted");//this has to be removed for the css to work.
			if (check){
				$("#password").addClass("is-valid");
				$("#password").removeClass("is-invalid");
				$("#passwordVerify").addClass("is-valid");
				$("#passwordVerify").removeClass("is-invalid");
				$("#passwordHelp").addClass("is-valid");
				$("#passwordHelp").removeClass("is-invalid");
			} else {
				$("#password").addClass("is-invalid");
				$("#password").removeClass("is-valid");
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
		function update(){
			$("#agreeHelp").removeClass("text-muted");//this has to be removed for the css to work.
			if (check){
				$("#agree").removeClass("is-invalid");
				$("#agreeHelp").addClass("is-valid");
				$("#agreeHelp").removeClass("is-invalid");
			} else {
				$("#agree").addClass("is-invalid");
				$("#agreeHelp").addClass("is-invalid");
				$("#agreeHelp").removeClass("is-valid");
			}
			$("#agreeHelp").html(message);
		}
		$.ajax({
			url: "http://2huchat.studiojaw.com/api?c=users&a=register",
			method:"POST",
			dataType:"json",
			data:{
				email:$("#email").val(),
				username:$("#username").val(),
				password:$("#password").val(),
				agree:$("#agree").prop("checked")
			},
			success:function(res, status, xhr){
				if (res.success){
					$("#register").hide();
					$("#registered").show();
				} else {
					checkEmail();
					checkUsername();
					checkPassword();
					if (res.errors && res.errors.agree ){
						check = false;
						message = "You must agree not to be a moron, or I won't let you make an account."
						update();
					} else {
						check = true;
						message = "";
						update();
					}
				}
			}
		})
		e.preventDefault();
	}

	function init(){
		$("#email").on("keyup", checkEmail );
		$("#username").on("keyup", checkUsername );
		$("#password").on("keyup", checkPassword );
		$("#passwordVerify").on("keyup", checkPassword );
		$("#regform").on("submit", submitAJAX );
	}
	/*********
	* Public
	**********/
	self.ajax = null;
	init();
}