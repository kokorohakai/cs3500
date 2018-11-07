<?php
class searchController{
	/**************
	* PRIVATE
	**************/
	private function findUser($data){
		$model = new Model("users");
		if (!isset($_REQUEST["username"])){
			$data["errors"]["username"] = "No username supplied";
			return $data;
		}
		$username = str_replace("'","''",$_REQUEST['username']);
		$data = $model->selectwhere(
			"username",
			"DESC",
			10,
			0,
			"username LIKE '%".$username."%'"
		);
		foreach ( $data as $key=>$value ){
			unset( $data[$key]["password"] );
			unset( $data[$key]["2"] );
		}
		return $data;
	}
	/**************
	* PUBLIC
	**************/
	public function __construct(){
	}
	public function execute($data){
		switch ( $_REQUEST["a"] ){
			case "user":
				$data = $this->findUser($data);
			break;
		}
		return $data;
	}
}