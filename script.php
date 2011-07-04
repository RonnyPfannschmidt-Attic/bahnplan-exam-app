<?php
require 'downloader.php';
require 'table_printers.php';

$time = new DateTime();
$content = file_get_contents(make_url("Berlin", $time)); #XXX: dynamic
$items = make_listing($content, $time);

array_walk($items, function(&$item) {
    $item["time"] = $item["time"]->format('d.m.y H:i');
});
$items = array_slice($items, 0, 10);

if(PHP_SAPI == 'cli')
    print draw_text_table($items);
else
    print draw_html_table($items);
?>
