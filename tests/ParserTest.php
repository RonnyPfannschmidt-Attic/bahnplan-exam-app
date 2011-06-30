
<?php


require 'downloader.php';

class ParserTest extends PHPUnit_Framework_TestCase
{
    function test_download_file()
    {
        $content = download_file('tests/out.html');
        $this->assertContains('Deutsche Bahn', $content);
        return $content;
    }


    /**
     * @depends test_download_file
     */
    function test_to_listing($content)
    {
        $listing = make_listing($content);
        print_r($listing);
    }


    public function test_foo()
    {
        return;
        $fp = fopen('http://mobile.bahn.de/bin/mobil/bhftafel.exe/dox?si=106907&bt=arr&ti=13:39&p=1111101&max=20&rt=1&use_realtime_filter=1&date=19.05.11&start=yes', 'r');
        $content = stream_get_contents($fp);

    }
}


?>
