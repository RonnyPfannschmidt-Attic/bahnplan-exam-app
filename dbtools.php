<?php

if (file_exists('dbconfig.php')) {
    include 'dbconfig.php';
    if(!isset($db))
        die('dbconfig.php with no $db is stupid\n');
} else {
    $db = new PDO("sqlite:dbfile.sqlite");
}

function get_current($limit=50, $before=5)
{
    $stmt = $db->prepare("select * from fahrplan
        where drift_arrival > (now() - ?) LIMIT ?;");
    $stmt->bindParam(0, $before);
    $stmt->bindParam(1, $limit);
    return $stmt->fetch_all();
}

function insert_or_update($station, $item)
{
    global $db;
    $stmt = $db->prepare("
        replace into fahrplan(
            station, train, line, 
            target, planed_arrival, drift) VALUES
            (?, ?, ?, ?, ?, ?)");
    if ($stmt == NULL)
        die(print_r($db->errorInfo(), true));
    $stmt->bindParam(1, $station, PDO::PARAM_STR);
    $stmt->bindParam(2, $item["train"], PDO::PARAM_STR);
    $stmt->bindParam(3, $item["line"], PDO::PARAM_STR);
    $stmt->bindParam(4, $item["target"], PDO::PARAM_STR);
    $stmt->bindParam(5, $item["time"]->getTimestamp(), PDO::PARAM_INT);
    $stmt->bindParam(6, $item["drift"], PDO::PARAM_STR);
    $stmt->execute() or die(print_r($stmt->errorInfo(), true));

}


?>
