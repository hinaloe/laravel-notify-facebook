<?php

namespace Hinaloe\Laravel\Channels\Facebook\Test\Channel;

use Facebook\Authentication\AccessToken;
use Hinaloe\Laravel\Channels\Facebook\Channel\FacebookChannel;
use Illuminate\Notifications\Notification;
use Mockery as m;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FacebookChannelTest extends TestCase
{
    /**
     * @var \Illuminate\Config\Repository|\Mockery\MockInterface
     */
    protected $config_mock;
    /**
     * @var \Illuminate\Routing\UrlGenerator|\Mockery\MockInterface
     */
    protected $url_mock;

    /**
     * @var LaravelFacebookSdk|m\MockInterface
     */
    protected $laravel_facebook_sdk;

    /**
     * @var FacebookChannel
     */
    protected $facebookChannel;

    public function setUp()
    {
        $this->config_mock = m::mock('Illuminate\Config\Repository');
        $this->url_mock = m::mock('Illuminate\Routing\UrlGenerator');
        $this->laravel_facebook_sdk = m::mock(new LaravelFacebookSdk($this->config_mock, $this->url_mock, [
            'app_id' => 'foo_id',
            'app_secret' => 'foo_secret',
            'persistent_data_handler' => 'memory',
        ]));
        $this->facebookChannel = new FacebookChannel($this->laravel_facebook_sdk);
    }

    public function tearDown()
    {
        m::close();
    }

    public function testSimplePass()
    {
        $notify = new class extends Notification
        {
            public function toFacebook()
            {
                return 'message';
            }
        };

        $notifable = new class
        {
            public function routeNotificationFor($to)
            {
                Assert::assertSame('facebook', $to);
                return 114514;
            }
        };

        $this->laravel_facebook_sdk
            ->shouldReceive('post')
            ->with('/114514/notifications', ['template' => 'message'], AccessToken::class)
            ->once();

        $this->facebookChannel->send($notifable, $notify);
    }

    /**
     * @expectedException \Hinaloe\Laravel\Channels\Facebook\Exceptions\InvalidIdException
     */
    public function testWithInvalidId()
    {
        $notify = new class extends Notification
        {
            public function toFacebook()
            {
                return 'message';
            }
        };

        $notifable = new class
        {
            public function routeNotificationFor($to)
            {
                Assert::assertSame('facebook', $to);
                return 'id';
            }
        };

        $this->laravel_facebook_sdk->shouldNotHaveReceived('post');

        $this->facebookChannel->send($notifable, $notify);
    }

    /**
     * @expectedException \Hinaloe\Laravel\Channels\Facebook\Exceptions\BadMessageException
     */
    public function testWithInvalidMessage()
    {
        $notify = new class extends Notification
        {
            public function toFacebook()
            {
                return new \stdClass();
            }
        };

        $notifable = new class
        {
            public function routeNotificationFor($to)
            {
                Assert::assertSame('facebook', $to);
                return 23456;
            }
        };

        $this->laravel_facebook_sdk->shouldNotHaveReceived('post');

        $this->facebookChannel->send($notifable, $notify);
    }
}
