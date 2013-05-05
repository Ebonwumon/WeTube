<?php
require_once("db.php");
/**
 * Created by JetBrains PhpStorm.
 * User: ebon
 * Date: 04/05/13
 * Time: 21:30
 * To change this template use File | Settings | File Templates.
 */

$db = new DB();

switch($_GET['type']) {
    case 'event': print json_encode($db->createEvent($_POST['user_id'], $_POST['name'], $_POST['desc']));
}