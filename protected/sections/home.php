<script src="/js/Home.like.js"></script>
<script src="/js/Home.js"></script>
<div class="container-fluid">
	<?php
	if ( !$_SESSION["LOGGEDIN"] ){
		require("../protected/sections/home.default.php");
	} else {
		if ( $user == ""){
			require("../protected/sections/home.noUser.php");
		} else {
			if ( strcmp($user, $_SESSION["USER"]["username"]) == 0 ){
				require("../protected/sections/home.myHome.php");
			} else {
				require("../protected/sections/home.user.php");
			}
		}
	}
	?>
</div>
