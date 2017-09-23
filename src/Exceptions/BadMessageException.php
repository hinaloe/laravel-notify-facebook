<?php

namespace Hinaloe\Laravel\Channels\Facebook\Exceptions;

class BadMessageException extends FacebookNotifyException
{
    public static function getInvalidMessage()
    {
        return new static('You must return string or `FacebookMessage` on `toFacebook` method.');
    }
}
