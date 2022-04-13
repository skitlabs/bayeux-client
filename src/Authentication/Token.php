<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Authentication;

class Token
{
    public function __construct(
        public readonly string $type,
        public readonly string $token,
        public readonly string $url
    ) { }
}
