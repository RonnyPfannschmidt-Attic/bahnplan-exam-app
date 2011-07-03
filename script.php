<?php
require 'downloader.php';

function draw_text_table ($table) {
    // Work out max lengths of each cell

    foreach ($table AS $row) {
        $cell_count = 0;
        foreach ($row AS $key=>$cell) {
            $cell_length = strlen($cell);
            $cell_count++;
            if (!isset($cell_lengths[$key]) || $cell_length > $cell_lengths[$key]) $cell_lengths[$key] = $cell_length;

        }
    }

    // Build header bar
    $bar = '+';
    $header = '|';
    $i=0;

    foreach ($cell_lengths AS $fieldname => $length) {
        $i++;
        $bar .= str_pad('', $length+2, '-')."+";

        $name = $i.") ".$fieldname;
        if (strlen($name) > $length) {
            // crop long headings

            $name = substr($name, 0, $length-1);
        }
        $header .= ' '.str_pad($name, $length, ' ', STR_PAD_RIGHT) . " |";

    }

    $output = '';

    $output .= $bar."\n";
    $output .= $header."\n";
    $output .= $bar."\n";

    // Draw rows

    foreach ($table AS $row) {
        $output .= "|";

        foreach ($row AS $key=>$cell) {
            $output .= ' '.str_pad($cell, $cell_lengths[$key], ' ', STR_PAD_RIGHT) . " |";

        }
        $output .= "\n";
    }

    $output .= $bar."\n";

    return $output;

}

function draw_html_table($items)
{
    echo '
<table>
  <tr>
    <th>target
    <th>time
    <th>train
    <th>line
    <th>drift
';
    foreach($items as $item)
    {
        echo "  <tr>\n";
        echo "    <td>${item['target']}\n";
        echo "    <td>${item['time']}\n";
        echo "    <td>${item['train']}\n";
        echo "    <td>${item['line']}\n";
        echo "    <td>${item['drift']}\n";
    }
    echo "</table>\n";

}

$content = file_get_contents(make_url()); #XXX: dynamic
$items = make_listing($content);
$items = array_slice($items, 0, 10);

if(PHP_SAPI == 'cli')
    print draw_text_table($items);
else
    print draw_html_table($items);
?>
