<?php

//XXX: get from configuration
global $db;
$db = new PDO("sqlite:dbfile.sqlite");

function get_current($limit=50, $before=5)
{
    $stmt = $db->prepare("select * from fahrplan
        where drift_arrival > (now() - ?) LIMIT ?;");
}

function insert_or_update($station, $data)
{
    $stmt = $db->prepare("
        insert into fahrplan(
            station, train, line, 
            target, time, drift) VALUES

            (?, ?, ?, ?, ?, ?)");

}


?>
