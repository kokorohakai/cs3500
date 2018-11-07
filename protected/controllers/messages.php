<?php
class messagesController{
	private function getLikes($id){
		$data = Model::dangerousExec("SELECT count(id) as c FROM likes WHERE message_id = ".$id);
		return $data[0]["c"];
	}

	private function getOne($data){
		$id = intval($_REQUEST["id"]);
		$data = $this->model->select( "id", $id );
		//filter out password and get likes count.
		foreach ( $data as $key=>$value ){
			$data[$key]["likes"] = $this->getLikes($value[0]);
			unset($data[$key]["password"]);
			unset($data[$key][9]);
		}
		return $data;

	}
	/*
	private function get($data){
		$startat = intval($_REQUEST["startat"]);
		$data = $this->model->select(
			"permission",
			0,
			"id",
			"DESC",
			10,
			$startat
		);
		//filter out password.
		foreach ( $data as $key=>$value ){
			unset($data[$key]["password"]);
			unset($data[$key][9]);
		}
		return $data;
	}
	*/

	private function getMyMessages(){
		$id = intval($_SESSION["USER"]["id"]);
		$where = "`users`.id = ".$id;
		$startat = intval($_REQUEST["startat"]);

		$data = $this->model->selectwhere(
			"id",
			"DESC",
			10,
			$startat,
			$where
		);
		//filter out password and get likes count.
		foreach ( $data as $key=>$value ){
			$data[$key]["likes"] = $this->getLikes($value[0]);
			unset($data[$key]["password"]);
			unset($data[$key][9]);
		}
		return $data;
	}

	private function getFollowedMessages(){
		$id = intval($_SESSION["USER"]["id"]);
		$where = 
			"(`follows`.follower_id = ".$id." OR ".
			"`mentions`.user_id = ".$id.") AND ".
			"`messages`.permission = 0";
		$startat = intval($_REQUEST["startat"]);

		$data = $this->model->selectwhere(
			"id",
			"DESC",
			10,
			$startat,
			$where
		);
		//filter out password and get likes count.
		foreach ( $data as $key=>$value ){
			$data[$key]["likes"] = $this->getLikes($value[0]);
			unset($data[$key]["password"]);
			unset($data[$key][9]);
		}
		return $data;

	}

	private function getUsersMessages(){
		$startat = intval($_REQUEST["startat"]);

		if (!isset($_REQUEST["username"])){
			$data["errors"]["message"] = "No user specified.";
			return $data;
		};

		$username = $_REQUEST["username"];

		$data = $this->model->selectmany(
			array(
				"users" => [ "username" => $username ],
				"permission" => 0
			),
			"id",
			"DESC",
			10,
			$startat
		);

		//filter out password and get likes count.
		foreach ( $data as $key=>$value ){
			$data[$key]["likes"] = $this->getLikes($value[0]);
			unset($data[$key]["password"]);
			unset($data[$key][9]);
		}
		return $data;
	}

	private function getByTag($data){
		$this->model = new Model("messages","id",[
			"user_id" => array( "users" => "id" ),
			"id" => array( "hashtags" => "message_id" ),
		]);
		$startat = intval($_REQUEST["startat"]);

		if (!isset($_REQUEST["tag"])){
			$data["errors"]["tag"] = "No tag specified.";
			return $data;
		};

		$tag = $_REQUEST["tag"];

		$data = $this->model->selectmany(
			array(
				"hashtags" => [ "tag" => $tag ],
				"permission" => 0
			),
			"id",
			"DESC",
			10,
			$startat
		);

		//filter out password and get likes count.
		foreach ( $data as $key=>$value ){
			$data[$key]["likes"] = $this->getLikes($value[0]);
			unset($data[$key]["password"]);
			unset($data[$key][9]);
		}
		return $data;
	}

	// no longer using due to the iframe method.
	// private function sendwall($data){
	// 	if (!isset($_REQUEST["message"])){
	// 		$data["errors"]["message"] = "No message specified.";
	// 		return $data;
	// 	}
	// 	if ( strlen($_REQUEST["message"]) < 1 ) {
	// 		$data["errors"]["message"] = "No message specified.";
	// 		return $data;
	// 	}
	// 	if ( !isset($_REQUEST["permission"])){
	// 		$data["errors"]["permission"] = "No permission set.";
	// 		return $data;
	// 	}
	// 	if ( $_REQUEST["permission"] < 0 || $_REQUEST["permission"] > 2 ){
	// 		$data["errors"]["permission"] = "Incorrect permission set.";
	// 		return $data;
	// 	}
	// 	if ( strlen($_REQUEST["message"]) > 200 ) {
	// 		$data["errors"]["message"] = "Message too big.";
	// 		return $data;
	// 	}

	// 	$values = [
	// 		"message" => $_REQUEST["message"],
	// 		"user_id" => intval( $_SESSION["USER"]["id"] ),
	// 		"permission" => intval( $_REQUEST["permission"] ),
	// 		"type" => "text" //fix this to support others! 
	// 	];
	// 	$data = $this->model->insert( $values );
	// 	return $data;
	// }
	/**************
	* PUBLIC
	**************/
	public function __construct(){
		$this->model = new Model("messages","id",[
			"user_id" => array( "users" => "id", "follows" => "user_id" ),
			"id" => array( "mentions" => "message_id" ),
		]);
	}
	public function execute($data){
		if ($_SESSION["LOGGEDIN"]){
			switch ( $_REQUEST["a"] ){
				// case "sendwall":
				// 	$data = $this->sendwall($data);
				// break;
				case "getmessage":
					$data = $this->getOne($data);
				break;
				case "get":
					$data = $this->get($data);
				break;
				case "getmymessages":
					$data = $this->getMyMessages($data);
				break;
				case "getfollowedmessages":
					$data = $this->getFollowedMessages($data);
				break;
				case "getbytag":
					$data = $this->getByTag($data);
				break;
				case "getusersmessages":
					$data = $this->getUsersMessages($data);
				break;
				default:
					$data["errors"]["action"] = "Action does not exist.";
				break;
			}
		} else {
			$data["errors"]["access"] = "Access Denied.";
		}
		return $data;
	}
}
