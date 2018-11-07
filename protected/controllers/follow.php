<?php
class followController{
  private function checkFollow($data){
    if (!isset($_REQUEST["username"])){
      $data["errors"]["username"] = "No user name specified to check.";
      return $data;
    }
    $userModel = new Model("users");
    $checkUser = $userModel->select("username",$_REQUEST["username"]);
    if (count($checkUser) == 0){
      $data["errors"]["username"] = "User does not exist.";
      return $data;
    }
    $followsModel = new Model("follows");
    $ret = $followsModel->selectmany([
      "follower_id" => $_SESSION["USER"]["id"],
      "user_id" => $checkUser[0]["id"]
    ]);
    if (count($ret) > 0){
      $data["success"] = true;
      $data["following"] = true;
    } else {
      $data["success"] = true;
      $data["following"] = false;
    }
    return $data;
  }

  private function follow($data){
    if (!isset($_REQUEST["username"])){
      $data["errors"]["username"] = "No user name specified to check.";
      return $data;
    }
    $userModel = new Model("users");
    $checkUser = $userModel->select("username",$_REQUEST["username"]);
    if (count($checkUser) == 0){
      $data["errors"]["username"] = "User does not exist.";
      return $data;
    }
    $followsModel = new Model("follows");
    $ret = $followsModel->selectmany([
      "follower_id" => $_SESSION["USER"]["id"],
      "user_id" => $checkUser[0]["id"]
    ]);
    if (count($ret) < 1 ){
      $followsModel->insert([
        "follower_id" => $_SESSION["USER"]["id"],
        "user_id" => $checkUser[0]["id"]
      ]);
      $data["success"] = true;
    } else {
      $data["errors"]["follow"] = "You are already following this user";
    }
    return $data;
  }

  private function unfollow($data){
    if (!isset($_REQUEST["username"])){
      $data["errors"]["username"] = "No user name specified to check.";
      return $data;
    }
    $userModel = new Model("users");
    $checkUser = $userModel->select("username",$_REQUEST["username"]);
    if (count($checkUser) == 0){
      $data["errors"]["username"] = "User does not exist.";
      return $data;
    }
    $followsModel = new Model("follows");
    $ret = $followsModel->selectmany([
      "follower_id" => $_SESSION["USER"]["id"],
      "user_id" => $checkUser[0]["id"]
    ]);
    if (count($ret) > 0){
      $data["success"] = true;
      $followsModel->delete( $ret[0]["id"] );
    } else {
      $data["errors"]["follow"] = "You are not following this user";
    }
    return $data;
  }

  public function __construct(){
    //probably will just create models as there need for the specific funciton.
  }

  //This controller is designed to do misc unrelated functions within the
  //application.s
  public function execute($data){
    switch ( $_REQUEST["a"] ){
      case "checkfollow":
        $data = $this->checkFollow($data);
        break;
      case "follow":
        $data = $this->follow($data);
        break;
      case "unfollow":
        $data = $this->unfollow($data);
        break;
      default:
        $data["errors"]["action"] = "Action does not exist.";
        break;
    }
    return $data;
  }
}
