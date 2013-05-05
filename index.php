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
print_r($db->playNext(1));

?>