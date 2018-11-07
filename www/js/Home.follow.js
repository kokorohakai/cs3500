App.HomeFollow = function( parent ){
  var following = false;

  function setFollow(){
    $("#followButton").addClass("btn-success");
    $("#followButton").removeClass("btn-primary");
    $("#followButton").html("Following");
    following = true;
  }

  function setUnfollow(){
    $("#followButton").removeClass("btn-success");
    $("#followButton").addClass("btn-primary");
    $("#followButton").html("Follow");
    following = false;
  }

  function checkFollow(){
    $.ajax({
      url: "http://2huchat.studiojaw.com/api?c=follow&a=checkfollow",
      method:"POST",
      dataType:"json",
      data: {
        username: parent.pageuser,
      },
      success:function(res, status, xhr){
        if (res.success){
          if (res.following){
            setFollow();
          } else {
            setUnfollow();
          }
          $("#followButton").on("click",toggleFollow)
        }
      }
    })
  }

  function toggleFollow(){
    if (following){
      unFollow();
    } else {
      follow();
    }
  }

  function follow(){
    $.ajax({
      url: "http://2huchat.studiojaw.com/api?c=follow&a=follow",
      method:"POST",
      dataType:"json",
      data: {
        username: parent.pageuser,
      },
      success:function(res, status, xhr){
        if (res.success){
          setFollow();
        }
      }
    })
  }

  function unFollow(){
    $.ajax({
      url: "http://2huchat.studiojaw.com/api?c=follow&a=unfollow",
      method:"POST",
      dataType:"json",
      data: {
        username: parent.pageuser,
        startat:0,
      },
      success:function(res, status, xhr){
        if (res.success){
          setUnfollow();
        }
      }
    })
  }

  function init(){
    checkFollow();
  }

  /*********
  * Public
  **********/
  init();
}
