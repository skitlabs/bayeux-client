<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Message;

use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;

abstract class Message
{
    protected string $channel = '/meta/connect';
    protected string $version = '1.0';
    protected string $minimumVersion = '1.0';
    protected ?string $clientId = null;
    protected string $id;
    protected string $timestamp;

    public function __construct()
    {
        $this->id = Uuid::uuid4()->toString();
        $this->timestamp = (new DateTimeImmutable())->format(DateTimeInterface::ATOM);
    }

    public function asArray() : array
    {
        $ref = new \ReflectionClass($this);

        $properties = [];
        foreach ($ref->getProperties() as $property) {
            if ($property->isPrivate()) {
                continue;
            }

            $value = $property->getValue($this);
            if (! $value) {
                continue;
            }

            $properties[$property->getName()] = $value;
        }

        return $properties;
    }
}
