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
    case 'event':
        switch($_GET['method']) {
            case 'create': print json_encode($db->createEvent($_POST['user_id'], $_POST['name'], $_POST['desc']));
        } break;
    break;
    case 'queue':
        switch($_GET['method']) {
            case 'add': print json_encode($db->addToQueue($_POST['event_id'], $_POST['youtube_id'], $_POST['youtube_name'], $_POST['user_id'])); break;
            case 'vote': print json_encode($db->vote($_POST['item_id'], $_POST['user_id'], $_POST['vote'])); break;
            default:
            case 'getAll': print json_encode($db->getAllInVoteQueue($_GET['event_id'])); break;
            case 'playNext': print json_encode($db->playNext($_POST['event_id'])); break;
            case 'getNowPlaying': print json_encode($db->getNowPlaying($_GET['event_id'])); break;
        }
    break;
    case 'user':
        switch($_GET['method']) {
            case 'login': print json_encode($db->login($_POST['username'])); break;
        }
    break;

}