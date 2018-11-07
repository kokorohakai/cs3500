<?php
class likeController{
  private function checkLikes($data){
    if (!isset($_REQUEST["messageid"])){
      $data["errors"]["messageid"] = "No message id to check.";
      return $data;
    }
    $messageModel = new Model("messages");
    $checkMessage = $messageModel->select("id",$_REQUEST["messageid"]);
    if (count($checkMessage) == 0){
      $data["errors"]["messageid"] = "Message does not exist.";
      return $data;
    }

    $likesModel = new Model("likes");
    //$likesModel->debug = true;
    $ret = $likesModel->selectmany([
      "user_id" => $_SESSION["USER"]["id"],
      "message_id" => $_REQUEST["messageid"]
    ]);
    if ( count($ret) > 0){
      $data["success"] = true;
      $data["liked"] = true;
    } else {
      $data["success"] = true;
      $data["liked"] = false;
    }
    return $data;
  }

  private function like($data){
    if (!isset($_REQUEST["messageid"])){
      $data["errors"]["messageid"] = "No user name specified to check.";
      return $data;
    }
    $messageModel = new Model("messages");
    $checkUser = $messageModel->select("id",$_REQUEST["messageid"]);
    if (count($checkUser) == 0){
      $data["errors"]["messageid"] = "User does not exist.";
      return $data;
    }
    $likesModel = new Model("likes");
    $ret = $likesModel->selectmany([
      "message_id" => $_REQUEST["messageid"],
      "user_id" => $_SESSION["USER"]["id"]
    ]);
    if (count($ret) < 1 ){
      $likesModel->insert([
        "message_id" => $_REQUEST["messageid"],
        "user_id" => $_SESSION["USER"]["id"]
      ]);
      $data["success"] = true;
    } else {
      $data["errors"]["like"] = "You are already liked this message.";
    }
    return $data;
  }

  private function unlike($data){
    if (!isset($_REQUEST["messageid"])){
      $data["errors"]["messageid"] = "No user name specified to check.";
      return $data;
    }
    $userModel = new Model("messages");
    $checkUser = $userModel->select("id", $_REQUEST["messageid"]);
    if (count($checkUser) == 0){
      $data["errors"]["messageid"] = "User does not exist.";
      return $data;
    }
    $likesModel = new Model("likes");
    $ret = $likesModel->selectmany([
      "message_id" => $_REQUEST["messageid"],
      "user_id" => $_SESSION["USER"]["id"]
    ]);
    if (count($ret) > 0){
      $likesModel->delete( $ret[0]["id"] );
      $data["success"] = true;
    } else {
      $data["errors"]["message"] = "You already do not like this message.";
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
      case "checklikes":
        $data = $this->checkLikes($data);
        break;
      case "like":
        $data = $this->like($data);
        break;
      case "unlike":
        $data = $this->unlike($data);
        break;
      default:
        $data["errors"]["action"] = "Action does not exist.";
        break;
    }
    return $data;
  }
}
