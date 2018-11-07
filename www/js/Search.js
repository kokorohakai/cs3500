App.Search = function(parent){
	var self = this;
	var xhr = undefined;

	function populateSearch( data ){
		console.log(data);
		html = '<div class="container-fluid">';
		for ( var i in data ){
			html += 
			'<a href="/' + data[i].username + '">'+
				'<div class="row row-eq-height border rounded">' +
					'<div class="row-eq-height col-sm">' +
						'<img src="' + parent.modules.Home.getAvatar(data[i]) + '" class="rounded-circle z-depth-1-half avatar-pic avatar-sm">' +
					'</div>' +
					'<div class="row-eq-height col-sm align-middle"><br>' +
						data[i].username +
					'</div>' +
				'</div>' +
			'</a>';
		}
		html+="</div>";
		$("#searchbox").html(html);
	}
	function search(){
		var username = $("#search").val();
		if (xhr) xhr.abort();
		$("#searchbox").html("");

		if (username.length > 0){
			$("#searchbox").removeClass("hide");
			$("#searchbox").addClass("show");
			xhr = $.ajax({
				url:"/api?c=search&a=user",
				dataType:"json",
				method:"post",
				data:{
					username:username
				},
				success:populateSearch
			})
		} else {
			xhr = undefined;
			$("#searchbox").addClass("hide");
			$("#searchbox").removeClass("show");
		}
	}
	function init(){
		$("#search").on("keyup",search);
	}

	/*********
	* Public
	**********/
	init();
}