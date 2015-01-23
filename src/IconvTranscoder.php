<?php

namespace Ddeboer\Transcoder;

use Ddeboer\Transcoder\Exception\ExtensionMissingException;
use Ddeboer\Transcoder\Exception\IllegalCharacterException;

class IconvTranscoder implements TranscoderInterface
{
    private $defaultEncoding;
    
    public function __construct($defaultEncoding = 'UTF-8')
    {
        if (!function_exists('iconv')) {
            throw new ExtensionMissingException('iconv');
        }
        
        $this->defaultEncoding = $defaultEncoding;
    }

    /**
     * {@inheritdoc}
     */
    public function transcode($string, $from = null, $to = null)
    {
        set_error_handler(
            function ($no, $message) use ($string) {
                throw new IllegalCharacterException($string, $message);
            },
            E_NOTICE | E_USER_NOTICE
        );
        
        $result = iconv($from, $to ?: $this->defaultEncoding, $string);
        restore_error_handler();
        
        return $result;
    }
}
