<?php
if ( $_SESSION["LOGGEDIN"] ) {
  if ( isset($_FILES["avatar"]) ){
    $file = $_FILES["avatar"];
    if ( $file["size"] < 1048576 ){
      if ( $file["type"] == "image/png" || $file["type"] == "image/jpeg" ){
        if ( $file["type"] == "image/png" ) {
          move_uploaded_file( $file["tmp_name"], "media/".$_SESSION["USER"]["uniquehash"].".png" );
        } else {
          move_uploaded_file( $file["tmp_name"], "media/".$_SESSION["USER"]["uniquehash"].".jpg" );
        }
        $model = new Model("users");
        $values = Array(
         "avatartype" => $file["type"],
        );
        $model->update( $_SESSION["USER"]["id"], $values );
        $_SESSION["USER"]["avatartype"] = $file["type"];
      }
    }
  }
  //if (isset($_REQUEST["submit"]))
  global $app;
  ?>
  <script src="/js/Account.js"></script>
  <div class="container" id="register">
  	<div class="row">
  		<div class="col-md bg-light" style="padding:30px">
  			<h1><?=$_SESSION["USER"]["username"]?></h1>
  			<form method="post" action="/?s=account" id="regform">
  			  <div class="form-group">
  			    <label for="email">Email address</label>
  			    <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email" disabled=disabled value="<?=$_SESSION["USER"]["email"]?>">
  			  </div>
  			  <div class="form-group">
  			    <label for="password">Change Password</label>
            <input type="password" class="form-control error" id="oldpassword" placeholder="Input your Old Password">
            <input type="password" class="form-control error" id="newpassword" placeholder="Input your New Password">
  			    <input type="password" class="form-control" id="passwordVerify" placeholder="Input your New Password again">
  			    <small id="passwordHelp" class="form-text text-muted">Make it more than 8 letters and good, then don't forget it. you lamer.</small>
  			  </div>
  			  <small id="updateHelp" class="form-text"></small>
          <br>
          <button type="submit" class="btn btn-primary">Submit</button>
  			</form>
  		</div>
      <div class="col-md-3 bg-light" class="text-left" style="padding:30px">
        <form method="post" action="/?s=account" id="avatar" class="md-form text-center" enctype="multipart/form-data">
          <h1>avatar</h1>
          <div class="file-field">
            <div class="mb-4">
                <img src="<?=APP::getAvatar($_SESSION["USER"])?>" class="rounded-circle z-depth-1-half avatar-pic avatar-lg" alt="You Avatar">
                <small id="avatarHelp" class="form-text text-muted">max 800x800</small>
            </div>
            <div class="d-flex justify-content-center">
                <div class="btn btn-mdb-color btn-rounded">
                    <button type="file" class="btn btn-primary" id="fileButton">Add photo</button><br>
                    <input type="file" class="hidden" name="avatar" id="avatarFile" style="display:none">
                </div>
            </div>
          </div>
        </form>
      </div>
  	</div>
  </div>
<?php
}
?>
