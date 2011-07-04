<?php
require('dbtools.php');
require 'downloader.php';
require 'table_printers.php';
global $db;

function create_db() {
    global $db;
    $res = $db->exec("create table fahrplan (
        station VARCHAR(30),
        target VARCHAR(30),
        planed_arrival BIGINT,
        drift_arrival BIGINT,
        train VARCHAR(30),
        line VARCHAR(30),
        drift VARCHAR(30),
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
    global $my_station;

    $time = new DateTime();
    $url = make_url($my_station, $time);
    $content = file_get_contents($url);
    $listing = make_listing($content, $time);
    foreach($listing as &$item)
        insert_or_update($my_station, $item);
}

function show_db()
{
    global $db;
    $items = get_current();
    print draw_text_table($items);
}
assert (PHP_SAPI == 'cli');


$cmds = array_slice($argv, 1);
foreach($cmds as $cmd) {
    call_user_func("{$cmd}_db");
}


?>
