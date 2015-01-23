<?php

namespace Ddeboer\Transcoder;

use Ddeboer\Transcoder\Exception\UnsupportedEncodingException;

class Transcoder implements TranscoderInterface
{
    private static $chain;
    
    /**
     * @var TranscoderInterface[]
     */
    private $transcoders = [];
    
    public function __construct(array $transcoders)
    {
        $this->transcoders = $transcoders;
    }

    public function transcode($string, $from = null, $to = null)
    {
        foreach ($this->transcoders as $transcoder) {
            try {
                return $transcoder->transcode($string, $from, $to);
            } catch (UnsupportedEncodingException $e) {
                
            }
        }
        
        throw $e;
    }

    /**
     * Create a transcoder
     * 
     * @param string $defaultEncoding
     *
     * @return TranscoderInterface
     *
     * @throws ExtensionMissingException
     */
    public static function create($defaultEncoding = 'UTF-8')
    {
        if (isset(self::$chain[$defaultEncoding])) {
            return self::$chain[$defaultEncoding];
        }
        
        $transcoders = [];
        
        try {
            $transcoders[] = new MbTranscoder($defaultEncoding);
        } catch (ExtensionMissingException $mb) {
            // Ignore missing mbstring extension; fallback to iconv
        }

        try {
            $transcoders[] = new IconvTranscoder($defaultEncoding);
        } catch (ExtensionMissingException $iconv) {
            throw $iconv;
        }
        
        self::$chain[$defaultEncoding] = new self($transcoders);

        return self::$chain[$defaultEncoding];
    }
}
