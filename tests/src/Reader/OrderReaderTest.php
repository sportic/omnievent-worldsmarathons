<?php

namespace Sportic\OmniEvent\Worldsmarathons\Tests\Reader;

use Sportic\OmniEvent\Models\Orders\RegistrationOrder;
use Sportic\OmniEvent\Worldsmarathons\Reader\OrderReader;
use  Sportic\OmniEvent\Worldsmarathons\Tests\AbstractTest;

class OrderReaderTest extends AbstractTest
{
    public function test_base()
    {
        $json = file_get_contents(TEST_FIXTURE_PATH . '/payloads/base.json');

        $order = OrderReader::from($json);

        self::assertInstanceOf(RegistrationOrder::class, $order);
        self::assertEquals('evt_d322742e3afa49329f6eeeb35afbe182', $order->getProperty('orderNumber'));
        self::assertEquals('1731431562', $order->getProperty('orderDate'));
        self::assertEquals('https://schema.org/OrderDelivered', $order->getProperty('orderStatus'));
    }
}