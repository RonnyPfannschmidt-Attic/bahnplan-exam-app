
<?php


require 'downloader.php';

class ParserTest extends PHPUnit_Framework_TestCase
{
    function test_download_file()
    {
        $content = file_get_contents('tests/out.html');
        $this->assertContains('Deutsche Bahn', $content);
        return $content;
    }


    /**
     * @depends test_download_file
     */
    function test_to_listing($content)
    {
        $listing = make_listing($content, new DateTime());
    }


    public function test_drift_cancelation()
    {
        $content = file_get_contents('tests/out_ausfall.html');
        $listing = make_listing($content, new DateTime());

    }
}


?>
