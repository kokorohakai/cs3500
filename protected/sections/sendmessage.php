<?php
	if ( $_SESSION["LOGGEDIN"] ){
		function sendMessage(){
			$data = array();
			$model = new Model("messages");
			$moveMedia = false;
			$type = "text";
			$limit = 200;
			$uniquehash = uniqid("media");

			if ( 	$_FILES["mediaFile"]["type"] == "image/png" ||
	      			$_FILES["mediaFile"]["type"] == "image/jpeg" ||
	      			$_FILES["mediaFile"]["type"] == "audio/mp3" ||
	      			$_FILES["mediaFile"]["type"] == "audio/wav" ||
	      			$_FILES["mediaFile"]["type"] == "video/ogg" ||
	      			$_FILES["mediaFile"]["type"] == "video/mp4" ){
				if ( $_FILES["mediaFile"]["type"] > 1048576 ) {
					$data["errors"]["message"] = "Unsupported Media Type.";
					return $data;		
				} else {
					$moveMedia = true;
					$type = $_FILES["mediaFile"]["type"];
					$limit = 25;
				}
			} else {
				$type = "text";
			}

			if (!isset($_REQUEST["message"])){
				$data["errors"]["message"] = "No message specified.";
				return $data;
			}
			if ( strlen($_REQUEST["message"]) < 1 ) {
				$data["errors"]["message"] = "No message specified.";
				return $data;
			}
			if ( !isset($_REQUEST["permission"])){
				$data["errors"]["permission"] = "No permission set.";
				return $data;
			}
			if ( $_REQUEST["permission"] < 0 || $_REQUEST["permission"] > 2 ){
				$data["errors"]["permission"] = "Incorrect permission set.";
				return $data;
			}
			if ( strlen($_REQUEST["message"]) > $limit ) {
				$data["errors"]["message"] = "Message too big.";
				return $data;
			}
			//parse the message
			$message = $_REQUEST["message"];

			//check if it's got hash tags.
			$values = [
				"message" => $message,
				"user_id" => intval( $_SESSION["USER"]["id"] ),
				"permission" => intval( $_REQUEST["permission"] ),
				"uniquehash" => $uniquehash,
				"type" => $type 
			];
			$id = $model->insert( $values );
			if ($moveMedia){
				$dest = "media/".$uniquehash;
				if ( $_FILES["mediaFile"]["type"] == "image/png" ) $dest .= ".png";
      			if ( $_FILES["mediaFile"]["type"] == "image/jpeg" ) $dest .= ".jpg";
      			if ( $_FILES["mediaFile"]["type"] == "audio/mp3" ) $dest .= ".mp3";
      			if ( $_FILES["mediaFile"]["type"] == "audio/wav" ) $dest .= ".wav";
      			if ( $_FILES["mediaFile"]["type"] == "video/ogg" ) $dest .= ".ogg";
      			if ( $_FILES["mediaFile"]["type"] == "video/mp4" ) $dest .= ".mp4";
      			echo $_FILES["mediaFile"]["tmp_name"];
      			echo $dest;
      			move_uploaded_file( $_FILES["mediaFile"]["tmp_name"], $dest );
			}

			$data["success"] = true;
			$data["messageid"] = $id;

			//check if it's got mentions:
			preg_match_all('/@[a-z0-9]*/i',$message, $matches );
			for ( $i = 0; $i < count($matches[0]); $i++ ){
				$mentionModel = new Model("mentions");
				$userModel = new Model("users");

				$user = substr($matches[0][$i],1);
				$userData = $userModel->select("username",$user);

				if (count($userData) > 0 ){
					$values = [
						"message_id" => $id, //CHANGE ME
						"user_id" => $userData[0]["id"]
					];
					$mentionModel->insert( $values );
				}
			}

			//check if it's got hashtags:
			$matches = [];
			preg_match_all('/#[a-z0-9]*/i',$message, $matches );
			for ( $i = 0; $i < count($matches[0]); $i++ ){
				$hashModel = new Model("hashtags");
				$tag = substr($matches[0][$i],1);
				$values = [
					"message_id" => $id,
					"tag" => $tag
				];
				$hashModel->insert( $values );
			}

			return $data;
		}

		$data = sendMessage();

		$data = json_encode($data);
		?>
		<script>
			var data = <?=$data;?>;
			window.top.App.MessageForm.Complete( data );
		</script>
		<?
	}
?>