	<?php
require("../protected/credentials.php");
require("../protected/model.php");
require("../protected/api.php");

class APP{
	private $api = NULL;

	public static function getAvatar( $user ){
		if ($user["avatartype"] == "image/png" ){
			$avatar = "/media/".$user["uniquehash"].".png";
		} elseif ($user["avatartype"] == "image/jpeg" ) {
			$avatar = "/media/".$user["uniquehash"].".jpg";
		} else {
			$avatar = "/img/avatar.png";
		}
  		
		return $avatar;
	}

	public function __construct(){

		//Determine the user and section:
		$section = "home";
		if ( isset($_REQUEST["s"]) ){
			$section = preg_replace("/[- .\/\\\?]/","_",$_REQUEST['s']);
		}

		//if the section is logout, well, logout.
		if ( $section == "logout" ){
		    unset($_SESSION["LOGGEDIN"]);
		    unset($_SESSION["USER"]);
			$section = "home";
		}

		//user
		$user = "";
		$userData = [];
		if (!empty($_REQUEST['url'])){
			$user = preg_replace("/[- .\/\\\?]/","_",$_REQUEST['url']);
		}

		//check if it's the API:
		if ( $user == "api" ){
			$api = new API();
			echo json_encode($api->exec());
		} else {
			if ( $user != "" ){
				$model = new Model("users");
				$userData = $model->select( "username", $user, '1', 'DESC', '1');
				if ( count($userData) > 0){
					$userData = $userData[0];
					unset($userData["PASSWORD"]);
				} else {
					$section = "404";
				}
			}
			require("../protected/layout.php");
		}
	}
}
