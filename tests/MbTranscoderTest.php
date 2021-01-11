<?php

namespace Ddeboer\Transcoder\Tests;

use Ddeboer\Transcoder\MbTranscoder;

class MbTranscoderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var MbTranscoder
     */
    private $transcoder;
    
    /**
     * @before
     */
    protected function doSetUp()
    {
        $this->transcoder = new MbTranscoder();
    }

    public function testTranscodeUnsupportedFromEncoding()
    {
        $this->expectException(\Ddeboer\Transcoder\Exception\UnsupportedEncodingException::class);
        $this->expectExceptionMessage('bad-encoding');
        $this->transcoder->transcode('bla', 'bad-encoding');
    }

    public function testTranscodeUnsupportedToEncoding()
    {
        $this->expectException(\Ddeboer\Transcoder\Exception\UnsupportedEncodingException::class);
        $this->expectExceptionMessage('bad-encoding');
        $this->transcoder->transcode('bla', null, 'bad-encoding');
    }
    
    public function testDetectEncoding()
    {
        $result = $this->transcoder->transcode('España', null, 'iso-8859-1');
        $this->transcoder->transcode($result);
    }
    
    public function testUndetectableEncoding()
    {
        $this->expectException(\Ddeboer\Transcoder\Exception\UndetectableEncodingException::class);
        $this->expectExceptionMessage('is undetectable');
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
