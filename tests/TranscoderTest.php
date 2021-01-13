<?php

namespace Ddeboer\Transcoder\Tests;

use Ddeboer\Transcoder\Transcoder;

class TranscoderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Transcoder
     */
    private $transcoder;
    
    /**
     * @before
     */
    protected function doSetUp()
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
