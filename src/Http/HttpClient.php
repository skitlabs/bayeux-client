<?php declare(strict_types=1);

namespace Skitlabs\Bayeux\Http;

interface HttpClient
{
    public function post(string $url, array $data) : array;
}
