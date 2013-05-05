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

?>
<script type="text/javascript" src="swfobject/swfobject.js"></script>    
  <div id="ytapiplayer">
        You need Flash player 8+ and JavaScript enabled to view this video.
          </div>

  <script type="text/javascript">

    var params = { allowScriptAccess: "always" };
    var atts = { id: "myytplayer" };
    swfobject.embedSWF("http://www.youtube.com/apiplayer?enablejsapi=1&version=3",
      "ytapiplayer", "960", "540", "8", null, null, params, atts);

    function onYouTubePlayerReady(playerid) {
      var player = document.getElementById("myytplayer");
      player.loadVideoById("E5mwp264g-Q");
      player.playVideo(); 
    }
      </script>
