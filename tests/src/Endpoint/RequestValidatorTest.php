<?php

namespace Sportic\OmniEvent\Worldsmarathons\Tests\Endpoint;

use Sportic\OmniEvent\Worldsmarathons\Endpoint\RequestValidator;
use Sportic\OmniEvent\Worldsmarathons\Tests\AbstractTest;
use Symfony\Component\HttpFoundation\Request;

class RequestValidatorTest extends AbstractTest
{

    /**
     * @param $secret
     * @param $return
     * @dataProvider data_validate
     */
    public function test_validate($secret, $return): void
    {
        $payload = '{}';

        $request = Request::create('http://example.com', 'POST', [], [], [], [], $payload);
        $request->headers->add(
            ['WM-Signature' => 't=1736688537,v1=560a8545ac1b4f93ab3f21bbcc30a2b636eb496c5806038f87dca05c640f1354'],
        );
        $requestValidator = RequestValidator::for($request, $secret);
        self::assertEquals($return, $requestValidator->validate());
    }

    public function data_validate(): array
    {
        return [
            ['sec_0aaf30b466464c1f8d0c7ff1d745bea2', true],
//            ['wrong', false],
        ];
    }
}
