<?php

namespace Hinaloe\Laravel\Channels\Facebook\Channel;

use Hinaloe\Laravel\Channels\Facebook\Exceptions\BadMessageException;
use Hinaloe\Laravel\Channels\Facebook\Exceptions\InvalidIdException;
use Hinaloe\Laravel\Channels\Facebook\Message\FacebookMessage;
use Illuminate\Notifications\Notification;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FacebookChannel
{
    /**
     * @var LaravelFacebookSdk
     */
    protected $facebookSdk;

    public function __construct(LaravelFacebookSdk $facebookSdk)
    {
        $this->facebookSdk = $facebookSdk;
    }

    public function send($notifable, Notification $notification)
    {
        $message = $notification->toFacebook($notifable);
        if (is_string($message)) {
            $message = FacebookMessage::fromString($message);
        }

        if (is_null($message)) {
            return;
        }

        if (!$id = $notifable->routeNotificationFor('facebook')) {
            return;
        }

        if (!$message instanceof FacebookMessage) {
            throw BadMessageException::getInvalidMessage();
        }

        $id = filter_var($id, FILTER_VALIDATE_INT, ['min_range' => 0]);

        if ($id === false) {
            throw InvalidIdException::badId();
        }

        $accessToken = $this->facebookSdk->getApp()->getAccessToken();

        $this->facebookSdk->post("/${id}/notifications", $message->toArray(), $accessToken);
    }
}
