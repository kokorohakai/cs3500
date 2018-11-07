<!DOCTYPE html>
<html>
	<meta charset="UTF-8">
	<head>
		<title>2Huchat</title>
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link type="text/css" rel="stylesheet" href="/css/layout.css"/>

		<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

	    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	    <script>
	    	Vars = {
	    		user: "<?=$_SESSION['USER']["username"];?>",
	    		userid: <?=$_SESSION['USER']["id"];?>
	    	}
	    </script>
	    <script src="/js/App.js"></script>
 	</head>
	<body>
		<nav class="container-fluid my-nav-bg">
			<div class="row">
				<div class="col-sm">
					<div class="dropdown">
						<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<?php echo $_SESSION["USER"]["username"]?>
						</button>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="/<?=$_SESSION["USER"]["username"];?>" class="button">My Home</a><br>
							<a class="dropdown-item" href="/?s=account" class="button">Account</a><br>
							<a class="dropdown-item" href="/?s=logout" class="button">Logout</a><br>
						</div>
					</div>
				</div>
				<div class="col-sm">
					<?php 
						require("../protected/search.php");
					?>
				</div>
				<div class="col-sm">
					<a href="/"><img src="/img/logo.png" class="logo"></a>
				</div>
			</div>
		</nav>
		<div class="container-fluid fun-divider">
			&nbsp;
		</div>
		<div class="container-fluid body-content">
			<?
			if (file_exists("../protected/sections/".$section.".php")) {
				require("../protected/sections/".$section.".php");
			} else {
				require("../protected/sections/404.php");
			}
			?>
		</div>
		<div class="container-fluid legal footer">
			<div class="row">
				<div class="col-sm">
					© 2015 Studio JAW. All rights reserved.
				</div>
			</div>
			<div class="row">
				<div class="col-sm">
				</div>
				<div class="col-sm">
				</div>
				<div class="col-sm">
					<a href="//www.studiojaw.com/terms">Site Terms of use</a>
				</div>
				<div class="col-sm">
					<a href="//www.studiojaw.com/privacy">Privacy Policy</a>
				</div>
				<div class="col-sm">
				</div>
				<div class="col-sm">
				</div>
			</div>
		</div>
 	</body>
</html>
