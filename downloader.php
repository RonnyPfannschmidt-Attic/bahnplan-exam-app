<?php


function scan_entry($node)
{
    $item = array();

    $link = $node->getElementsByTagName('a')->item(0);
    $target = $link->nextSibling->wholeText;
    $target = trim($target);
    $target = trim($target, "(<>");
    $target = trim($target);
    $target = str_replace("\n", ' ', $target);
    $item['target'] = utf8_decode($target);

    $spans = $node->getElementsByTagName('span');

    $time_span = $spans->item(1);
    $item["time"] = trim($time_span->firstChild->wholeText);

    $train_span = $spans->item(0);
    $train = $train_span->firstChild->wholeText;
    $train = preg_split('/\s+/', $train);
    $item["train"] = implode(' ', $train);


    $line_or_extra = $time_span->parentNode->lastChild;

    if ($line_or_extra instanceof DomElement) {
        $line = $line_or_extra->previousSibling->previousSibling->wholeText;
    } else {
        $line = $line_or_extra->wholeText;
    }


    $line = str_replace('k.A.,', '', $line);
    $line = trim($line, ", \t");
    $item["line"] = trim($line, "\xC2\xA0\n");

    $drift_span = $spans->item(2);
    if($drift_span)
    {
        $drift = $drift_span->firstChild->wholeText;
        $drift = trim($drift);
        $drift = str_replace("\n", ' ', $drift);
        $item["drift"] = utf8_decode($drift);
    }
    else
        $item["drift"] = "";

    return $item;
}

function scan_page($content)
{
    // die bahn liefert kaputtes html
    $tidy = new Tidy();
    $content = $tidy->repairString($content);
    $doc = new DOMDocument();
    $doc->loadHtml($content);
    $doc->normalizeDocument();

    $divs = $doc->getElementsByTagName('div');
    $length = $divs->length;
    $listing = array();

    for ($pos=0; $pos<$length; $pos++)
    {
        $node = $divs->item($pos);

        $class = $node->getAttribute('class');
        if ($class == "sqdetailsDep trow")
            $listing[] = scan_entry($node);
    }
    return $listing;

}



/* make a url for a station at the current date/time
 * the default station is Gotha
 *
 * */
function make_url($station, $datetime)
{
    $time = $datetime->format('H:i');
    $date = $datetime->format('d.m.y');
    $url = "http://mobile.bahn.de/bin/mobil/bhftafel.exe/dox?".
        "si=$station&bt=dep".
        "&ti=$time".
        "&date=$date".
        "&p=1111101&max=100&rt=1&use_realtime_filter=1".
        "&start=yes";
    return $url;
}

function add_timestamps(&$item, $key, $basetime)
{
    $itemtime = clone $basetime;
    $comparetime = clone $basetime;
    // needed for the case where basetime
    // is a few seconds more than the fixed itemtime
    $comparetime->modify('- 5 minutes');
    $timeit = explode(':', $item["time"]);
    $itemtime->setTime($timeit[0], $timeit[1], 0);
    if ($itemtime < $comparetime)
        $itemtime->modify('+1 day');
    $item["time"] = $itemtime;
}


function make_listing($content, $datetime)
{
    $listing = scan_page($content);
    array_walk($listing, 'add_timestamps', $datetime);
    return $listing;
}

?>
