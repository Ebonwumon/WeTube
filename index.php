<!DOCTYPE html>
<?php
require_once('db.php');
/**
 * Created by JetBrains PhpStorm.
 * User: ebon
 * Date: 04/05/13
 * Time: 23:51
 * To change this template use File | Settings | File Templates.
 */
$db = new DB();
$event_id = 1; //TODO
?>
<html>
<head>
    <link rel="stylesheet" href='bootstrap/css/bootstrap.min.css'>
</head>
<body>
<div class="masthead">
    <h3 class="muted">WeTube</h3>
</div>

<div id="event_id" style="display:none;">
    <?php echo $event_id; ?>
</div>
<script type="text/javascript" src="jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="swfobject/swfobject.js"></script>    
<div class="row-fluid"><div class="span10 offset1">
<div id="ytapiplayer">
        You need Flash player 8+ and JavaScript enabled to view this video.
          </div>
        </div></div>

<div class ="row">
<div class="span10 offset1">
<table id="queue" class="table">
<thead>
<tr><th>Name</th><th>Votes</th><th>Requester</th></tr>
</thead>
</table>
</div>
</div>

<script type="text/javascript">
function insertTableRow(data) {
    $('#queue').prepend("<tr id=tr"+data.ID+"><td>"+data.video_name+"</td><td>"+data.vote+"</td><td>"+data.username+"</td></tr>");
    }  

    $(document).ready(function() {
      $.get("api.php?type=queue&method=getAll&event_id=" + event_id, function(data) {
        data = JSON.parse(data);
        for (var v in data) {
          insertTableRow(data[v]);
        }
      });
    });
    var player = document.getElementById("myytplayer");
    var params = { allowScriptAccess: "always" };
    var event_id = $('#event_id').html();
    var atts = { id: "myytplayer" };
    swfobject.embedSWF("http://www.youtube.com/apiplayer?&enablejsapi=1&version=3",
      "ytapiplayer", "960", "540", "8", null, null, params, atts);

    function onYouTubePlayerReady(playerid) {
      var player = document.getElementById("myytplayer");

        player.addEventListener("onStateChange", "onPlayerStateChange");
        $.get("api.php?type=queue&method=getNowPlaying&event_id=" + event_id, function(data) {
            data = JSON.parse(data);
            player.loadVideoById(data.youtube_id);
            player.playVideo();
        });
    }

    function onPlayerStateChange(state) {
        switch (state) {
            case 0: playNextVideo(); break;
        }
    }

    function playNextVideo() {
      var player = document.getElementById("myytplayer");

        $.ajax({
            url: "api.php?type=queue&method=playNext",
            type: "POST",
            data: { event_id: event_id },
            success: function(data) {
              data = JSON.parse(data);
              console.log(data);
              $('#tr'+data.ID).hide('fast');
              var id = data.youtube_id;
                player.loadVideoById(id);
                player.playVideo();
            }
        });
    }


          
      </script>

</body>
</html>
