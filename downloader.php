<?php

function download_file($url)
{
    $fp = fopen($url, 'r');
    $content = stream_get_contents($fp);
    fclose($fp);
    return $content;
}

function downloader(string $url)
{
}

?>
