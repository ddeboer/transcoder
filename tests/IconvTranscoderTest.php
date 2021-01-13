<?php

namespace Ddeboer\Transcoder\Tests;

use Ddeboer\Transcoder\IconvTranscoder;

class IconvTranscoderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var IconvTranscoder
     */
    private $transcoder;
    
    /**
     * @before
     */
    protected function doSetUp()
    {
        $this->transcoder = new IconvTranscoder();
        // Passing null (empty encoding name) to iconv makes it detect encoding from locale.
        // The phpunit-bridge sets locale to C for consistency but that implies ASCII.
        // This file uses UTF-8 so we have to set the locale accordingly.
        $this->setLocale(\LC_ALL, 'C.UTF-8');
    }
    
    public function testTranscodeUnsupportedFromEncoding()
    {
        $this->expectException(\Ddeboer\Transcoder\Exception\UnsupportedEncodingException::class);
        $this->expectExceptionMessage('bad-encoding');
        $this->transcoder->transcode('bla', 'bad-encoding');
    }
    
    public function testDetectEncoding()
    {
        $this->transcoder->transcode('España', null, 'iso-8859-1');
    }

    public function testTranscodeIllegalCharacter()
    {
        $this->expectException(\Ddeboer\Transcoder\Exception\IllegalCharacterException::class);
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
