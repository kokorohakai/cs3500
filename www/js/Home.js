App.Home = function(parent){
  var self = this;

  function getAvatar( msg ){
    var avatar = "img/avatar.png";
    if ( msg.avatartype == "image/png"){
      avatar = "/media/"+msg.uniquehash+".png";
    } else if (msg.avatartype == "image/jpeg") {
      avatar = "/media/"+msg.uniquehash+".jpg";
    }
    return avatar;
  }
  /*
    List Messages system.
  */
  self.addMessage = function( msg, bottom = true ){
    function getImage(){
      var html=""
      if (msg.type != "text"){
          if (msg.type == "image/png"){
            html = '<img src="/media/' + msg["6"] + '.png" class="img-fluid">';
          }
          if (msg.type == "image/jpeg"){
            html = '<img src="/media/' + msg["6"] + '.jpg" class="img-fluid">';
          }
          if (msg.type == "video/mp4"){
            html =  '<video controls class="embed-responsive">'+
                      '<source src="/media/' + msg["6"] + '.mp4" type="audio/mpeg">' +
                      'We\'re not sorry your browser is old and crap, please use a browser that doesn\'t suck.' +
                    '</video>'
          }
          if (msg.type == "audio/mp3"){
            html =  '<audio controls class="embed-responsive">'+
                      '<source src="/media/' + msg["6"] + '.mp3" type="audio/mpeg">' +
                      'We\'re not sorry your browser is old and crap, please use a browser that doesn\'t suck.' +
                    '</audio>'
          }
          if (msg.type == "audio/wav"){
            html =  '<audio controls class="embed-responsive">'+
                      '<source src="/media/' + msg["6"] + '.wav" type="audio/wav">' +
                      'We\'re not sorry your browser is old and crap, please use a browser that doesn\'t suck.' +
                    '</audio>'
          }
      }
      return html;
    }

    function tagMessage( msgStr ){
      var regex = /@[a-z0-9]*/gi
      var users = msgStr.match(regex);
      if ( users ){
        for ( var i = 0; i < users.length; i++ ){
          var replace = users[i].substr(1);
          replace = '<a href="/'+replace+'">'+replace+'</a>'
          msgStr = msgStr.replace( users[i], replace);
        }
      }

      var regex = /#[a-z0-9]*/gi
      var tags = msgStr.match(regex);
      if ( tags ){
        for ( var i = 0; i < tags.length; i++ ){
          var replace = tags[i].substr(1);
          replace = '<a href="/?tag='+replace+'">#'+replace+'</a>'
          msgStr = msgStr.replace( tags[i], replace);
        }
      }
      return msgStr;
    }

    var usermsg = "<i>" + msg.likes + " people like this";
    if ( msg.follower_id == parent.userid ) usermsg += ", You follow";
    if ( msg[20] == parent.userid ) usermsg += ", Mentioned You";
    usermsg += "</i><br>";
    html =
      '<div class="card mb-5">' +
        '<div class="card-header">' +
          '<div class="row">' +
            '<div class="mb-1 col-2">' +
              '<img src="'+getAvatar(msg)+'" class="rounded-circle z-depth-1-half avatar-pic avatar-sm">' +
            '</div>' +
            '<div class="mb-1 col-6">' +
              '<a href="/' + msg.username + '">' + msg.username + '</a><br>' + usermsg + msg.timestamp +
            '</div>' +
            '<div class="mb-1 col-4 text-right">'+
              ( (msg.permission=="0") ?
              '<span class="perm-sm public"></span>' :
              '<span class="perm-sm private"></span>' ) +
            '</div>' +
          '</div>' +
        '</div>' +
        '<div class="card-body">' +
        tagMessage( msg.message ) +
        '</div>' +
        getImage() + 
        '<div class="card-footer">' +
          '<button class="btn btn-light like-button" messageid="'+ msg[0] + '" id="likeButton' + msg[0] + '">'+
            '<span id="likeIcon'+msg[0]+'" class="likes unlike"></span>'+
            '<span id="likeMessage'+msg[0]+'">Like</span>'+
          '</button>&nbsp;&nbsp;'+
          '<button class="btn btn-primary">Reply</button>'+
        '</div>' +
      '</div>';
    if (bottom){
      $("#messages").append(html);
    } else {
      $("#messages").prepend(html);
    }
    parent.modules.HomeLike.checkLike( msg[0] );
  }
  //no init(), but that's fine. This is just a shared set of methods.
  /***********
  * Public
  ************/
  self.getAvatar = getAvatar; //uh... whatever.
}
