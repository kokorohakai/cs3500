<?php
class pubmessagesController{
	private function getPopular(){
		$startat = intval($_REQUEST["startat"]);

		$sqlStr = 
			"select messages.*, users.*, COUNT(likes.id) AS likes ".
			"FROM messages ".
			"LEFT JOIN likes ON messages.id = likes.message_id ".
			"LEFT JOIN users ON messages.user_id = users.id ".
			"WHERE `messages`.permission = 0 ".
			"GROUP BY likes.message_id ".
			"ORDER BY likes DESC";
		$data = MODEL::dangerousExec($sqlStr);
		foreach( $data as $key=>$value){
			unset($data[$key]["password"]);
			unset($data[$key]["10"]);
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
			case "getpopular":
				$data = $this->getPopular($data);
			break;
		}
		return $data;
	}
}
