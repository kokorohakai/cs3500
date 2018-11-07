<?php
class usersController{
  /**************
	* PRIVATE
	**************/
  private $model = NULL;
  private $minUNSize = 1;
  private $minPWSize = 8;
  private $minEMSize = 1;

  //checks the database to see if the username is already been used.
  private function checkUserNameExists( $username ){
		$retval = false;
		$check = $this->model->select( "username", $username, '1', 'DESC', '1' );

		if ( count($check) > 0) {
			$retval = true;
		}
		return $retval;
  }

  private function checkUsername( $data ){
    if ( !isset($_REQUEST["username"]) || strlen($_REQUEST["username"]) < $this->minUNSize ){
      $data["errors"]["username"] = "Username is too short.";
    } else {
      if ( $this->checkUserNameExists( $_REQUEST["username"]) ){
        $data["errors"]["username"] = "Username already taken.";
      }
    }
    if ( count($data["errors"]) == 0 ){
      $data["success"]=true;
    }
    return $data;
  }

  //checks the database to see if a user with the e-mail address already exists.
  private function checkEmailExists( $email ){
		$retval = false;
		$check = $this->model->select( "email", $email, '1', 'DESC', '1' );

    if ( count($check) > 0) {
			$retval = true;
		}
		return $retval;

    return false;
  }

  private function checkEmail( $email ){
    if ( !isset($_REQUEST["email"]) || strlen($_REQUEST["email"]) < $this->minEMSize ){
      $data["errors"]["email"] = "Please provide an e-mail address";
    } else {
      if ( !filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL ) ){
        $data["errors"]["email"] = "Invalid E-mail Address";
      } else {
        if ( $this->checkEmailExists( $_REQUEST["email"] ) ){
          $data["errors"]["email"] = "Email address in use by another account.";
        }
      }
    }
    if ( count($data["errors"]) == 0 ){
      $data["success"]=true;
    }

    return $data;
  }

  private function register($data){
    if ( $_REQUEST["agree"] != "true" ){
      $data["errors"]["agree"] = "You did not agree to the terms of service.";
      return $data;
    }
    if ( !isset($_REQUEST["username"]) || strlen($_REQUEST["username"]) < $this->minUNSize ){
      $data["errors"]["username"] = "username is too short.";
      return $data;
    } else {
      if ( $this->checkUserNameExists( $_REQUEST["username"]) ){
        $data["errors"]["username"] = "username already taken.";
        return $data;
      }
    }
    if( preg_match('/[a-z0-9]*/i', $_REQUEST["username"], $match ) ){
      if ( $match[0] != $_REQUEST["username"] ){
        $data["errors"]["username"] = "username can only have characters A-Z, 0-9.";
        return $data;
      }
    }
    if ( !isset($_REQUEST["password"]) || strlen($_REQUEST["password"]) < $this->minPWSize ){
      $data["errors"]["password"] = "password is too short.";
      return $data;
    }
    if ( !isset($_REQUEST["email"]) || strlen($_REQUEST["email"]) < $this->minEMSize ){
      $data["errors"]["email"] = "Please provide an e-mail address";
      return $data;
    } else {
      if ( !filter_var($_REQUEST["email"], FILTER_VALIDATE_EMAIL ) ){
        $data["errors"]["email"] = "Invalid E-mail Address";
        return $data;
      } else {
        if ( $this->checkEmailExists( $_REQUEST["username"]) ){
        	$data["errors"]["email"] = "Email address in use by another account.";
          return $data;
        }
      }
    }

		//prepare some data for our database.
		$hashpassword = password_hash(
			$_REQUEST["password"]."2huchat", //custom salt.
			PASSWORD_BCRYPT
		);
		$uniquehash = uniqid("user");

		//prepare the values we want to put in the database.
		$values = array(
			"username" => $_REQUEST["username"],
			"email" => $_REQUEST["email"],
			"password" => $hashpassword,
			"uniquehash" => $uniquehash
		);

		//insert it to the database.
		$this->model->insert($values);

		//prepare the activation e-mail:
		$subject = "Welcome ".$username." to 2huchat!";
		$body = "Before you can continue, you must activate your account!\n".
		"\n".
		"You can activate your account by copying and pasting this link into your browser:\n".
    "http://2huchat.studiojaw.com/?s=activate&key=".$uniquehash."\n";

  	$headers = "From: none@studiojaw.com\r\nReply-To:none@studiojaw.com";
  	//ship it!
		mail( $_REQUEST["email"], $subject, $body, $headers );

		//let the client know we did it!
		$data["success"]=true;
    return $data;
  }

  private function login( $data ){
    if (!isset($_REQUEST["password"])){
      $data["errors"]["password"] = "You must supply a password.";
    }
    if (!isset($_REQUEST["email"])){
      $data["errors"]["email"] = "You must supply an e-mail address.";
    } else {
      $email = $_REQUEST["email"];
      $password = $_REQUEST["password"];

      $check = $this->model->select( "email", $email, '1', 'DESC', '1' );
      if ( count($check) > 0 ){
        if ($check[0]["activated"]=="1"){
          if (password_verify( $password."2huchat", $check[0]["password"]) ){
            $_SESSION["LOGGEDIN"] = true;
            $_SESSION["USER"] = $check[0];
            unset($check[0]["PASSWORD"]);
            $data["success"]=true;
          } else {
            $data["errors"]["password"] = "Incorrect password.";
          }
        } else {
          $data["errors"]["account"] = "Account is not activated.";
        }
      } else {
        $data["errors"]["email"] = "Account does not exist.";
      }
    }
    return $data;
  }

  private function update(){
    if ($_SESSION["LOGGEDIN"]){
      $user = $this->model->select( "id", $_SESSION["USER"]["id"], '1', 'DESC', '1' );
      $values = [];
      //if the old password was set, then you want to update your password, right?
      if (isset($_REQUEST["oldpassword"]) && strlen($_REQUEST["oldpassword"]) > 0){
          if (!password_verify($_REQUEST["oldpassword"]."2huchat", $user[0]["password"])){
              $data["errors"]["password"] = "Old password is incorrect.";
              return $data;
          }
          if ( !isset($_REQUEST["newpassword"]) || strlen($_REQUEST["newpassword"]) < $this->minPWSize ){
              $data["errors"]["password"] = "New password is too short.";
              return $data;
          }
          if ( $_REQUEST["newpassword"] != $_REQUEST["passwordverify"] ) {
              $data["errors"]["password"] = "Verification password does not match new password.";
              return $data;
          }
          //if all checks pass, then set anew!
          $hashpassword = password_hash(
              $_REQUEST["newpassword"]."2huchat", //custom salt.
              PASSWORD_BCRYPT
          );
          $values["password"] = $hashpassword;
      }
      //$values can be adjusted later to contain any other user information we add onto this. However, password was a special case,
      //since it needed to be verified.
      $this->model->update($_SESSION["USER"]["id"], $values);
      $data["success"]=true;
    } else {
      $data["errors"]["account"] = "You need to be logged in to use this.";
    }
    return $data;
  }

  private function logout(){
    unset($_SESSION["LOGGEDIN"]);
    unset($_SESSION["USER"]);
  }

  /**************
	* PUBLIC
	**************/
  public function __construct(){
    $this->model = new Model("users");
  }

  public function execute($data){
    switch ( $_REQUEST["a"] ){
      case "register":
        $data = $this->register($data);
        break;
      case "checkemail":
        $data = $this->checkEmail($data);
        break;
      case "checkusername":
        $data = $this->checkUsername($data);
        break;
      case "login":
        $data = $this->login($data);
        break;
      case "logout":
        $data = $this->logout();
        break;
      case "update":
        $data = $this->update();
        break;
      default:
        $data["errors"]["action"] = "Action does not exist.";
        break;
    }
    return $data;
  }
}
?>
