<?php
$title = "Error";
$message = "You must supply a key for which user you are trying to activate.";

$model = new Model("users");
if (isset($_REQUEST["key"])){
	$res = $model->select("uniquehash",$_REQUEST["key"]);
	if ( sizeof($res) > 0 ){
		if ($res[0]["activated"]=="0"){
			$title = "Thank You!";
			$message = "Your account is now activated! You may now log in.";
			$id = $res[0]["id"];
			$model->update($id, ["activated"=>"1"]);
		} else {
			$message = "Your account is already activated.";
		};
	} else {
		$message = "This key does not exist!";
	}
} 

?>
<div class="container">
	<div class="row">
		<div class="col-sm">
			<div class="card bg-light centered" style="width:338px">
				<div class="card-body">
					<h1 class="card-title"><?=$title;?></h1>
					<p class="card-text"><?=$message;?></p>
					<a href="/" class="btn btn-primary">Go home</a>
				</div>
			</div>
		</div>
	</div>
</div>