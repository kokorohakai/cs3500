<!DOCTYPE html>
<html>
	<meta charset="UTF-8"> 
	<head>
		<title>2Huchat</title>
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
		<link type="text/css" rel="stylesheet" href="/css/layout.css"/>

		<!--script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script-->
		<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>

	    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	    <script src="/js/App.js"></script>
 	</head>
	<body>
		<nav class="container-fluid my-nav-bg">
			<div class="row">
				<div class="col-sm">
					<div class="dropdown">
						<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Navigate
						</button>
						<div class="dropdown-menu">
							<a class="dropdown-item" href="/" class="button">Home</a><br>
							<a class="dropdown-item" href="/?s=register" class="button">Register</a><br>
							<a class="dropdown-item" href="/?s=login" class="button">Login</a><br>
						</div>
					</div>
				</div>
				<div class="col-sm">
					<img src="/img/logo.png" class="logo">
				</div>
				<div class="col-sm">
					<img src="/img/header.png" class="graphic">
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