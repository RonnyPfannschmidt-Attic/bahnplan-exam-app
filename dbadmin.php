<?php
require('dbtools.php');
require 'downloader.php';
require 'table_printers.php';
global $db;

function format_time(&$item) {
    $item["time"] = $item["time"]->format('d.m.y H:i');
}

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
    array_slice($items, 'format_time');
    if(PHP_SAPI == 'cli')
        print draw_text_table($items);
    else
        print draw_html_table($items);
}

$web_commands = array(
    'sync' => "sync the database with the bahn",
    'show' => "show the table",
    'create' => "create the database tables");

if(PHP_SAPI == 'cli') {
    $cmds = array_slice($argv, 1);
    foreach($cmds as $cmd) {
        call_user_func("{$cmd}_db");
    }
} else {
    if(isset($_GET["cmd"]))
        $cmd = $_GET["cmd"];
    else
        $cmd = null;
?>
<title> bahn admin - doing <?= $cmd or 'nuthing' ?></title>
<?php
    if(isset($web_commands[$cmd]))
        call_user_func("{$cmd}_db");
    else if($cmd === null)
        echo 'NUll<br>\n';
    else
        echo "$cmd not allowed<br>\n";

?>
<h1> Bahn Abfahrplan Admin
<form>
    <select name="cmd">
        <option value="create">create the db</option>
        <option value="sync">sync from the bahn</option>
    </select>
    <input type=submit>
</form>
<?php
}
?>
