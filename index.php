<?php
require_once("db.php");

$db = new DB();

print_r($db->vote(1, 1, 1));