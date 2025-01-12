<?php

namespace Sportic\OmniEvent\Worldsmarathons\Endpoint;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestValidator
{
    public const HEADER_SIGNATURE = 'WM-Signature';

    protected ServerRequestInterface|Request $request;
    protected SignedRequest $signedRequest;

    /**
     * @param ServerRequestInterface|Request $request
     */
    public function __construct(ServerRequestInterface|Request $request)
    {
        $this->request = $request;
        $this->signedRequest = new SignedRequest();
    }

    public static function for(ServerRequestInterface|Request $request, $secret): RequestValidator
    {
        $validator = new static($request);
        $validator->signedRequest->secret = $secret;
        return $validator;
    }

    public function validate()
    {
        $this->populateHeaderParts();
        $this->populatePayload();
        return $this->validateSignature();
    }

    public function calculateSignature()
    {
        $stringToSign = $this->signedRequest->timestamp . '.' . $this->signedRequest->payload;
        return hash_hmac('sha256', $stringToSign, $this->signedRequest->secret);
    }

    public function validateSignature()
    {
        return hash_equals($this->calculateSignature(), $this->signedRequest->signature);
    }

    protected function populateHeaderParts(): void
    {
        $header = property_exists($this->request, 'headers')
            ? $this->request->headers->get(self::HEADER_SIGNATURE)
            : $this->request->getHeaderLine(self::HEADER_SIGNATURE);
        if ($header === null) {
            return;
        }
        $parts = explode(',', $header);

        $timestamp = explode('=', $parts[0]);
        $this->signedRequest->timestamp = $timestamp[1];

        $signature = explode('=', $parts[1]);
        $this->signedRequest->signature = $signature[1];
    }

    protected function populatePayload(): void
    {
        $payload = null;
        if ($this->request instanceof Request) {
            $payload = $this->request->getContent();
        } elseif (method_exists($this->request, 'getBody')) {
            $payload = $this->request->getBody()->getContents();
        }
        $this->signedRequest->payload = $payload;
    }

}

