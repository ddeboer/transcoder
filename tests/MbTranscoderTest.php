<?php

namespace Ddeboer\Transcoder\Tests;

use Ddeboer\Transcoder\MbTranscoder;

class MbTranscoderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MbTranscoder
     */
    private $transcoder;
    
    protected function setUp()
    {
        $this->transcoder = new MbTranscoder();
    }

    /**
     * @expectedException \Ddeboer\Transcoder\Exception\UnsupportedEncodingException
     * @expectedExceptionMessage bad-encoding
     */
    public function testTranscodeUnsupportedFromEncoding()
    {
        $this->transcoder->transcode('bla', 'bad-encoding');
    }

    /**
     * @expectedException \Ddeboer\Transcoder\Exception\UnsupportedEncodingException
     * @expectedExceptionMessage bad-encoding
     */
    public function testTranscodeUnsupportedToEncoding()
    {
        $this->transcoder->transcode('bla', null, 'bad-encoding');
    }
    
    public function testDetectEncoding()
    {
        $result = $this->transcoder->transcode('España', null, 'iso-8859-1');
        $this->transcoder->transcode($result);
    }
    
    /**
     * @expectedException \Ddeboer\Transcoder\Exception\UndetectableEncodingException
     * @expectedExceptionMessage is undetectable 
     */
    public function testUndetectableEncoding()
    {
        $result = $this->transcoder->transcode(
            '‘curly quotes make this incompatible with 1252’',
            null,
            'windows-1252'
        );
        $this->transcoder->transcode($result);
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
            ['‘España’', 'windows-1252'],
            ['España', 'iso-8859-1']
        ];
    }
}
