<?php

namespace Ddeboer\Transcoder\Tests;

use Ddeboer\Transcoder\Transcoder;

class TranscoderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Transcoder
     */
    private $transcoder;
    
    protected function setUp()
    {
        $this->transcoder = Transcoder::create();
    }

    /**
     * @dataProvider getStrings
     */
    public function testTranscode($string, $encoding)
    {
        $result = $this->transcoder->transcode($string, 'UTF-8', $encoding);
        $this->assertEquals($string, $this->transcoder->transcode($result, $encoding));
    }
    
    public function getStrings()
    {
        return [
            ['España', 'UTF-8'],
            ['bla', 'windows-1257'] // Encoding only supported by iconv
        ];
    }
}
