<?php
require('dbtools.php');
global $db;

function create_db() {
    global $db;
    $res = $db->exec("create table fahrplan (
        station,
        target,
        planed_arrival DATETIME,
        drift_arrival DATETIME,
        train,
        line,
        drift,
    PRIMARY KEY(station, target, planed_arrival, train, line)
);");
    if ($res !== 0)
        die(print_r($db->errorInfo(), true));
}

function clear_db()
{
    $db->exec("drop from fahrplan where 1");
}

assert (PHP_SAPI == 'cli');


$cmds = array_slice($argv, 1);
foreach($cmds as $cmd) {
    call_user_func("{$cmd}_db");
}


?>
