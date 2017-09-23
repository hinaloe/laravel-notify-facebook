<?php

namespace Hinaloe\Laravel\Channels\Facebook\Test;

use PHPUnit\Framework\TestCase;
use Mockery as m;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class ExampleTest extends TestCase
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
     * @var LaravelFacebookSdk|\Mockery\MockInterface
     */
    protected $laravel_facebook_sdk;

    public function setUp()
    {
        $this->config_mock = m::mock('Illuminate\Config\Repository');
        $this->url_mock = m::mock('Illuminate\Routing\UrlGenerator');
        $this->laravel_facebook_sdk = m::mock(new LaravelFacebookSdk($this->config_mock, $this->url_mock, [
            'app_id' => 'foo_id',
            'app_secret' => 'foo_secret',
            'persistent_data_handler' => 'memory',
        ]));
    }

    public function tearDown()
    {
        m::close();
    }

    public function test_is_true()
    {
        $this->assertTrue(true);
    }

    public function test_get_access_token()
    {
        $this->assertSame('foo_id|foo_secret', $this->laravel_facebook_sdk->getApp()->getAccessToken()->getValue());
    }
}
