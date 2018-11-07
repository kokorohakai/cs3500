<?
if ( $_REQUEST["nolayout"] == "1"){
	if (file_exists("../protected/sections/".$section.".php")) {
		require("../protected/sections/".$section.".php");
	} else {
		require("../protected/sections/404.php");
	}
} else {
	if ( $_SESSION["LOGGEDIN"] == true ){
		require("../protected/loggedin.php");
	} else {
		require("../protected/loggedout.php");
	}
}