<?php


function scan_entry($node)
{
    $item = array();

    $link = $node->getElementsByTagName('a')->item(0);
    $target = $link->nextSibling->wholeText;
    $target = trim($target);
    $target = trim($target, "(<>");
    $target = trim($target);
    $item['target'] = utf8_decode($target);

    $spans = $node->getElementsByTagName('span');

    $time_span = $spans->item(1);
    $item["time"] = $time_span->firstChild->wholeText;

    $train_span = $spans->item(0);
    $train = $train_span->firstChild->wholeText;
    $train = preg_split('/\s+/', $train);
    $item["train"] = implode(' ', $train);

    $line = $time_span->parentNode->lastChild->wholeText;
    $line = str_replace('k.A.,', '', $line);
    $line = trim($line, ", \t");
    $item["line"] = $line;

    $drift_span = $spans->item(2);
    if($drift_span)
    {
        $drift = $drift_span->firstChild->wholeText;
        $drift = trim($drift);
        $item["drift"] = utf8_decode($drift);
    }
    else
        $item["drift"] = "";

    return $item;
}

function make_listing($content)
{
    // die bahn liefert kaputtes html
    $content = str_replace('class="noBG"', '', $content);

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
        if ($class == "sqdetails trow")
            $listing[] = scan_entry($node);
    }
    return $listing;

}




function make_url()
{
    $service_url_format="http://mobile.bahn.de/bin/mobil/bhftafel.exe/dox?".
        "si=008010136&bt=arr". #Gotha
        "&ti=%H:%M".
        "&date=%d.%m.%g".
        "&p=1111101&max=100&rt=1&use_realtime_filter=1".
        "&start=yes";
    $d = strftime($service_url_format);
    return $d;
}

?>
