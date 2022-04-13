<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Transport;

use Skitlabs\Bayeux\Message\Message;

interface Transport
{
    public function send(string $url, Message ... $messages) : array;

    public function forceAuthenticate() : void;
}
