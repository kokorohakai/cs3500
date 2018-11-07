App.Login = function(){
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

	function checkEmail(e){
		var check = false;
		var val = $("#email").val();
		
		function update(){
			if (check){
				$("#email").addClass("is-valid");
				$("#email").removeClass("is-invalid");
			} else {
				$("#email").addClass("is-invalid");
				$("#email").removeClass("is-valid");
			}
		}

		if (validateEmail(val)){
			check = true;
		} else {
			check = false;
		}
		update();
	}
	function submitAJAX(e){
		var message = "";
		function update(){
			$("#errorHelp").html(message);
		}
		$.ajax({
			url: "http://2huchat.studiojaw.com/api?c=users&a=login",
			method:"POST",
			dataType:"json",
			data:{
				email:$("#email").val(),
				password:$("#password").val(),
			},
			success:function(res, status, xhr){
				if (res.success){
					window.location.href = "/";
				} else {
					checkEmail();
					if ( res.errors ){
						console.log(res.errors);
						for ( i in res.errors ){
							message+=res.errors[i]+"<br>";
						}
						update();
					} 
				}
			}
		})
		e.preventDefault();
	}

	function init(){
		$("#email").on("keyup", checkEmail );
		$("#regform").on("submit", submitAJAX );
	}
	/*********
	* Public
	**********/
	self.ajax = null;
	init();
}