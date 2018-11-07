<script src="/js/Home.myHome.js"></script>
<div class="row mb-5">
  <div class="col text-center">
    <img src="<?=APP::getAvatar($_SESSION["USER"])?>" class="rounded-circle z-depth-1-half avatar-pic avatar-lg" alt="You Avatar">
    <h1><?=$_SESSION["USER"]["username"]?></h1>
    <?
    $id = $_SESSION["USER"]["id"];
    $followers = Model::dangerousExec("SELECT count(id) AS c FROM follows WHERE user_id = ".$id)[0]["c"];
    $following = Model::dangerousExec("SELECT count(id) AS c FROM follows WHERE follower_id = ".$id)[0]["c"];
    ?>
    <table class="table">
      <thead>
        <th>Followers</th>
        <th>Following</th>
      </thead>
      <tr>
        <td><?=$followers?></td>
        <td><?=$following?></td>
      </tr>
    </table>
  </div>

  <div class="col text-right">
    <?php require("../protected/sections/messageForm.php"); ?>
    <div id="messages" class="text-left">
    </div>
  </div>
  <div class="col">
    <?php require("../protected/sections/suggested.php"); ?>
  </div>
</div>
