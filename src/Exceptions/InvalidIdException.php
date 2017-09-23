<?php

namespace Hinaloe\Laravel\Channels\Facebook\Exceptions;

class InvalidIdException extends FacebookNotifyException
{
    public static function badId()
    {
        return new static('You must return integer Facebook ID on Notifable::routeNotificationForFacebook.');
    }
}