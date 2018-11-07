App.HomeLike = function( parent ){
  var self = this;
  var likes = {};

  function setLike( id ){
    $("#likeButton"+id).addClass("btn-danger");
    $("#likeButton"+id).removeClass("btn-primary");
    $("#likeButton"+id).removeClass("btn-light");
    $("#likeIcon"+id).addClass("like");
    $("#likeIcon"+id).removeClass("unlike");
    $("#likeMessage"+id+" ").html("Liked");
    likes[id] = true;
  }

  function setUnlike( id ){
    $("#likeButton"+id).removeClass("btn-danger");
    $("#likeButton"+id).addClass("btn-primary");
    $("#likeButton"+id).removeClass("btn-light");
    $("#likeIcon"+id).removeClass("like");
    $("#likeIcon"+id).addClass("unlike");
    $("#likeMessage"+id+" ").html("Like");
    likes[id] = false;
  }

  function toggleLike( e ){
    var id = $(e.currentTarget).attr("messageid");
    if (likes[id]){
      unlike(id);
    } else {
      like(id);
    }
  }

  function like( id ){
    $.ajax({
      url: "http://2huchat.studiojaw.com/api?c=like&a=like",
      method:"POST",
      dataType:"json",
      data: {
        messageid: id,
      },
      success:function(res, status, xhr){
        if (res.success){
          setLike( id );
        }
      }
    })
  }

  function unlike( id ){
    $.ajax({
      url: "http://2huchat.studiojaw.com/api?c=like&a=unlike",
      method:"POST",
      dataType:"json",
      data: {
        messageid: id,
        startat:0,
      },
      success:function(res, status, xhr){
        if (res.success){
          setUnlike( id );
        }
      }
    })
  }

  function init(){
    //this will be triggered after new messages are loaded.
  }

  /*****************
  * Public
  *****************/
  self.checkLike = function( id ){
    $.ajax({
      url: "http://2huchat.studiojaw.com/api?c=like&a=checklikes",
      method:"POST",
      dataType:"json",
      data: {
        messageid: id,
      },
      success:function(res, status, xhr){
        $("#likeButton"+id).on("click",toggleLike);
        if (res.success){
          if (res.liked){
             setLike( id );
          } else {
             setUnlike( id );
          }
        }
      }
    })
  }

  init();
}
