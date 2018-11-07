<script src="/js/MessageForm.js"></script>
<form id="messageForm" enctype="multipart/form-data" target="messageFormFrame" method="post" action="/?s=sendmessage&nolayout=1">
  <div class="form-group">
    <textarea class="form-control" id="message" name="message" rows="3" placeholder="What bs do you want to let loose on the world today?"></textarea>
    <small class="form-text text-muted" id="messageHelp">Characters Left: <span id="messageCount">200</span></small>
    <small class="form-text is-invalid" id="messageErrors"></small>
  </div>
  <div class="btn-group mb-5" role="group" aria-label="submit">
    <button type="file" class="btn btn-primary" id="mediaFileButton">Add Media</button><br>
    <input type="file" class="hidden" name="mediaFile" id="mediaFile" style="display:none">

    <button type="submit" id="messageSubmit" class="btn btn-primary" disabled="disabled">Hue!</button>

    <button type="button" id="messagePermButton"
      class="btn btn-primary dropdown-toggle"
      data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <span value="0" class="dropdown-item perm-sm public"></span>
    </button>
    <input type="hidden" value="0" name="permission" id="messagePerm">
    <div class="dropdown-menu perm-sm-layout" id="permMenu">
      <span value="0" class="dropdown-item perm-sm public"></span>
      <!--span value="1" class="dropdown-item perm-sm friends"></span-->
      <span value="2" class="dropdown-item perm-sm private"></span>
    </div>
  </div>
</form>
<iframe src="about:blank" name="messageFormFrame" id="messageFormFrame" style="display:none"></iframe>