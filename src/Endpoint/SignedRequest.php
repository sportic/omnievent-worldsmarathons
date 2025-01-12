<?php

namespace Sportic\OmniEvent\Worldsmarathons\Endpoint;

use JsonSerializable;
use Nip\Utility\Serializable\Serializable;

class SignedRequest implements JsonSerializable, \Serializable
{
    use Serializable;

    public ?string $payload = null;
    public ?string $signature = null;

    public ?string $secret = null;

    public ?string $timestamp = null;


    public function __serialize(): array
    {
        return [
            'payload' => $this->payload,
            'signature' => $this->signature,
            'secret' => $this->secret,
            'timestamp' => $this->timestamp,
        ];
    }

    public function __unserialize($data): void
    {
        $this->payload = $data['payload'] ?? null;
        $this->signature = $data['signature'] ?? null;
        $this->secret = $data['secret'] ?? null;
        $this->timestamp = $data['timestamp'] ?? null;
    }
}