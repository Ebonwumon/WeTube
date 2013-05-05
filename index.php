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

<div id="event_id" style="display:none;">
    <?php echo $event_id; ?>
</div>
<script type="text/javascript" src="jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="swfobject/swfobject.js"></script>    
  <div id="ytapiplayer">
        You need Flash player 8+ and JavaScript enabled to view this video.
          </div>

  <script type="text/javascript">

    var params = { allowScriptAccess: "always" };
    var event_id = $('#event_id').html();
    var atts = { id: "myytplayer" };
    swfobject.embedSWF("http://www.youtube.com/apiplayer?enablejsapi=1&version=3",
      "ytapiplayer", "960", "540", "8", null, null, params, atts);

    function onYouTubePlayerReady(playerid) {
        ytplayer.addEventListener("onStateChange", "onytplayerStateChange");

        $.get("api.php?type=queue&method=getNowPlaying&event_id=" + event_id, function(data) {
            data = JSON.parse(data);
            player.loadVideoById(data.youtube_id);
            player.playVideo();
        });

    function onytPlayerStateChange(state) {
        switch (state) {
            case 0: playNextVideo(); break;
        }
    }

    function playNextVideo() {
        $.ajax({
            url: "api.php?type=queue&method=playNext",
            type: "POST",
            data: { event_id: event_id },
            success: function(data) {
                data = JSON.parse(data);
                player.loadVideoById(data.youtube_id);
                player.playVideo();
            }
        });
    }


      var player = document.getElementById("myytplayer");
      /*player.loadVideoById("E5mwp264g-Q");
      player.playVideo(); */
    }
      </script>
