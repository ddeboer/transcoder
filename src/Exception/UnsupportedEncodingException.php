<?php

namespace Ddeboer\Transcoder\Exception;

class UnsupportedEncodingException extends \RuntimeException
{
    public function __construct($encoding)
    {
        parent::__construct(sprintf('Encoding %s is unsupported on this platform', $encoding));
    }
}
