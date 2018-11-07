<script src="/js/Home.otherUser.js"></script>
<script src="/js/Home.follow.js"></script>
<div class="row mb-5">
  <div class="col text-center">
    <img src="<?=APP::getAvatar($userData)?>" class="rounded-circle z-depth-1-half avatar-pic avatar-lg" alt="<?=$user;?>'s Avatar">
    <h1><?=$user?></h1>
    <?
    $id = $userData["id"];
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
    
    <button class="btn btn-primary" id="followButton">Follow</button>
  </div>

  <div class="col text-right">
    <div id="messages" class="text-left">
    </div>
  </div>

  <div class="col">
  </div>
</div>
