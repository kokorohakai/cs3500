<?php
class pubmessagesController{
	private function getPopular(){
		$startat = intval($_REQUEST["startat"]);

		$sqlStr = 
			"select COUNT(likes.id) AS likescount, messages.*, users.* ".
			"FROM messages ".
			"LEFT JOIN likes ON messages.id = likes.message_id ".
			"LEFT JOIN users ON messages.user_id = users.id ".
			"GROUP BY likes.message_id ".
			"ORDER BY likescount DESC";
		$data = MODEL::dangerousExec($sqlStr);
			
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
