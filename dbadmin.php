<?php
require('dbtools.php');
require 'downloader.php';
global $db;

function create_db() {
    global $db;
    $res = $db->exec("create table fahrplan (
        station VARCHAR,
        target VARCHAR,
        planed_arrival DATETIME,
        drift_arrival DATETIME,
        train VARCHAR,
        line VARCHAR,
        drift VARCHAR,
    PRIMARY KEY(station, target, planed_arrival, train, line)
);");
    if ($res !== 0)
        die(print_r($db->errorInfo(), true));
}

function clear_db()
{
    global $db;
    $db->exec("drop from fahrplan where 1");
}

function kill_db()
{
    global $db;
    $db->exec("drop table fahrplan");
}

function sync_db()
{
    global $db;

    $station = "Berlin";

    $time = new DateTime();
    $url = make_url($station, $time);
    $content = file_get_contents($url);
    $listing = make_listing($content, $time);
    foreach($listing as &$item)
        insert_or_update($station, $item);
}

assert (PHP_SAPI == 'cli');


$cmds = array_slice($argv, 1);
foreach($cmds as $cmd) {
    call_user_func("{$cmd}_db");
}


?>
