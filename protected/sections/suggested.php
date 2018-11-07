<div class="suggested" id="suggested">
	<h4>Suggested Users:</h4>
	<div class="container">
<?
	$sqlStr = 
	"SELECT * FROM `users` ".
	"WHERE `users`.`id` NOT IN ".
		"(SELECT `user_id` ".
		"FROM `follows` ".
		"WHERE `follower_id` = ".$_SESSION["USER"]["id"].") ".
	"AND `users`.`zipcode` = ".$_SESSION["USER"]["zipcode"]." ".
	"AND `users`.`id` != ".$_SESSION["USER"]["id"];
	
	$users = Model::dangerousExec($sqlStr);
	foreach ( $users as $user ){
		?>
		<a href="/<?=$user["username"]?>">
			<div class="row">
				<div class="col-sm-3">
					<img src="<?=APP::getAvatar($user)?>" class="rounded-circle z-depth-1-half avatar-pic avatar-sm">
				</div>
				<div class="col-lg">
					<br>
					<?=$user["username"]?>
				</div>
			</div>
		</a>
		<?
	}
?>
	</div>
</div>