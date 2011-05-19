<?php

function download_file($url)
{
    $url = (string) $url;
    $fp = fopen($url, 'r');
    $content = stream_get_contents($fp);
    fclose($fp);
    return (string)$content;
}


function scan_entry($node)
{
    $item = array();

    $link = $node->getElementsByTagName('a')->item(0);
    $target = $link->nextSibling->wholeText;
    $target = trim($target);
    $target = trim($target, "(<>");
    $target = trim($target);
    $item['target'] = $target;

    $spans = $node->getElementsByTagName('span');

    $time_span = $spans->item(1);
    $item["time"] = $time_span->firstChild->wholeText;

    $train_span = $spans->item(0);
    $train = $train_span->firstChild->wholeText;
    $train = preg_split('/\s+/', $train);
    $item["train"] = implode(' ', $train);

    $line = $time_span->parentNode->lastChild->wholeText;
    $line = trim($line, ",");
    $line = trim($line);
    $item["line"] = $line;

    $drift_span = $spans->item(2);
    if($drift_span)
    {
        $drift = $drift_span->firstChild->wholeText;
        $drift = trim($drift);
        $item["drift"] = $drift;
    }

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


?>
