<?php

namespace Hinaloe\Laravel\Channels\Facebook\Test\Message;

use Hinaloe\Laravel\Channels\Facebook\Message\FacebookMessage;
use PHPUnit\Framework\TestCase;

class FacebookMessageTest extends TestCase
{
    /** @var  FacebookMessage */
    protected $message;

    protected function setUp()
    {
        $this->message = new FacebookMessage();
        parent::setUp();
    }

    public function testWithTemplateToArray()
    {
        $this->message->template('This is an test message!');

        $toArray = $this->message->toArray();
        $this->assertArrayNotHasKey('href', $toArray);
        $this->assertSame(['template' => 'This is an test message!'], $toArray);
    }

    public function testFromString()
    {
        $this->assertSame(['template'=>'Hello!'], FacebookMessage::fromString('Hello!')->toArray());
    }

    /**
     * @expectedException \Hinaloe\Laravel\Channels\Facebook\Exceptions\CouldNotCreateMessage
     */
    public function testInvalidType()
    {
        $this->message->type('invalid');
    }
}
