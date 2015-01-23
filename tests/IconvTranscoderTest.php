<?php

namespace Ddeboer\Transcoder\Tests;

use Ddeboer\Transcoder\IconvTranscoder;

class IconvTranscoderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IconvTranscoder
     */
    private $transcoder;
    
    protected function setUp()
    {
        $this->transcoder = new IconvTranscoder();
    }
    
    /**
     * @expectedException \Ddeboer\Transcoder\Exception\UnsupportedEncodingException
     * @expectedExceptionMessage bad-encoding
     */
    public function testTranscodeUnsupportedFromEncoding()
    {
        $this->transcoder->transcode('bla', 'bad-encoding');
    }
    
    public function testDetectEncoding()
    {
        $this->transcoder->transcode('España', null, 'iso-8859-1');
    }

    /**
     * @expectedException \Ddeboer\Transcoder\Exception\IllegalCharacterException
     */
    public function testTranscodeIllegalCharacter()
    {
        $this->transcoder->transcode('“', null, 'iso-8859-1');
    }

    /**
     * @dataProvider getStrings
     */
    public function testTranscode($string, $encoding)
    {
        $result = $this->transcoder->transcode($string, null, $encoding);
        $this->assertEquals($string, $this->transcoder->transcode($result, $encoding));
    }
    
    public function getStrings()
    {
        return [
            ['España', 'iso-8859-1']
        ];
    }
}
