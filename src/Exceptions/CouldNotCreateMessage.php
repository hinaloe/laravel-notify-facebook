<?php

namespace Hinaloe\Laravel\Channels\Facebook\Exceptions;

class CouldNotCreateMessage extends FacebookNotifyException
{
    public static function invalidNotificationType()
    {
        return new static('Invalid notification type provided.');
    }
}
