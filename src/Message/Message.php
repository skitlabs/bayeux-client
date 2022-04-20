<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Message;

use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;
use Skitlabs\Bayeux\Context;

class Message
{
    protected array $properties = [];

    public function __construct(array $properties, Context $context)
    {
        $this->properties = array_merge([
            'channel' => '/meta/connect',
            'version' => '1.0',
            'minimumVersion' => '1.0',
            'clientId' => '',
            'id' => Uuid::uuid4()->toString(),
            'timestamp' => (new DateTimeImmutable())->format(DateTimeInterface::ATOM),
        ], $properties);

        $this->withContext($context);
    }

    public function get(string $key, mixed $default = null) : mixed
    {
        return $this->properties[$key] ?? $default;
    }

    public function extend(string $extension, array $properties) : void
    {
        $this->properties['ext'] ??= [];

        $this->properties['ext'][$extension] = $properties;
    }

    public function isMeta(?string $channel = null) : bool
    {
        if (! str_starts_with($this->get('channel', ''), '/meta/')) {
            return false;
        }

        return str_ends_with($this->get('channel'), '/' . $channel);
    }

    public function asArray() : array
    {
        return $this->properties;
    }

    protected function withContext(Context $context) : self
    {
        $this->properties = array_merge($this->properties, ['clientId' => $context->clientId()]);

        return $this;
    }
}
