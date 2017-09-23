<?php

namespace Hinaloe\Laravel\Channels\Facebook\Message;

use Hinaloe\Laravel\Channels\Facebook\Enums\NotificationType;
use Hinaloe\Laravel\Channels\Facebook\Exceptions\CouldNotCreateMessage;
use Illuminate\Contracts\Support\Arrayable;

class FacebookMessage implements Arrayable
{
    /**
     * @var string
     */
    public $template;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $ref;

    /**
     * @var string
     */
    public $type;

    /**
     * @param string $template
     * @return static
     */
    public static function fromString(string $template)
    {
        return (new static())->template($template);
    }

    public function template(string $template)
    {
        $this->template = $template;
        return $this;
    }

    public function url(string $url)
    {
        $this->url = $url;
        return $this;
    }

    public function href(string $url)
    {
        return $this->url($url);
    }

    public function ref(string $ref)
    {
        $this->ref = $ref;
        return $this;
    }

    public function type(string $type)
    {
        if (!in_array($type, [NotificationType::GENERIC, NotificationType::CONTENT_UPDATE], true)) {
            throw CouldNotCreateMessage::invalidNotificationType();
        }
        $this->type = $type;
        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_filter([
            'template' => $this->template,
            'href' => $this->url,
            'ref' => $this->ref,
            'type' => $this->type,
        ]);
    }
}
