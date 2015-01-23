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

    public function testDetectEncoding()
    {
        $this->transcoder->transcode('España', "UTF-8", 'iso-8859-1');
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
