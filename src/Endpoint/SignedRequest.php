<?php

namespace Sportic\OmniEvent\Worldsmarathons\Endpoint;

class SignedRequest
{
    public ?string $payload = null;
    public ?string $signature = null;

    public ?string $secret = null;

    public ?string $timestamp = null;

}