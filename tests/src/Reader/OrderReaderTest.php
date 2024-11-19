<?php

namespace Sportic\OmniEvent\Worldsmarathons\Tests\Reader;

use Spatie\SchemaOrg\Invoice;
use Sportic\OmniEvent\Models\Orders\RegistrationOrder;
use Sportic\OmniEvent\Models\Participants\Participant;
use Sportic\OmniEvent\Models\Races\Race;
use Sportic\OmniEvent\Models\Registrations\EventRegistration;
use Sportic\OmniEvent\Worldsmarathons\Reader\OrderReader;
use  Sportic\OmniEvent\Worldsmarathons\Tests\AbstractTest;
use function PHPUnit\Framework\assertInstanceOf;

class OrderReaderTest extends AbstractTest
{
    public function test_base()
    {
        $json = file_get_contents(TEST_FIXTURE_PATH . '/payloads/two_participants.json');
        $json = json_decode($json, true);

        $order = OrderReader::from($json['data']);
        $this->test_base_order($order);

        $invoice = $order->getProperty('partOfInvoice');
        $this->test_base_invoice($invoice);

        $orderItems = $order->getProperty('orderedItem');
        self::assertCount(2, $orderItems);
        foreach ($orderItems as $key => $orderItem) {
            $this->test_base_order_item($orderItem, $key + 1);
        }
    }

    protected function test_base_order($order)
    {
        self::assertInstanceOf(RegistrationOrder::class, $order);
        self::assertEquals('2024-1677050', $order->getProperty('orderNumber'));
        self::assertEquals('1731394486', $order->getProperty('orderDate'));
    }

    /**
     * @param mixed $invoice
     * @return void
     */
    protected function test_base_invoice($invoice): void
    {
        self::assertInstanceOf(Invoice::class, $invoice);

        $totalPaymentDue = $invoice->getProperty('totalPaymentDue');
        self::assertEquals('EUR', $totalPaymentDue->getProperty('currency'));
        self::assertEquals('81', $totalPaymentDue->getProperty('value'));
    }

    protected function test_base_order_item(mixed $orderItem, int|string $key)
    {
        self::assertInstanceOf(\Spatie\SchemaOrg\OrderItem::class, $orderItem);

        $registration = $orderItem->getProperty('orderedItem');
        self::assertInstanceOf(EventRegistration::class, $registration);

        $race = $registration->getRace();
        self::assertInstanceOf(Race::class, $race);
        self::assertEquals('T2-20240725032109824-1', $race->getProperty('identifier'));
        self::assertEquals('Half-marathon', $race->getProperty('name'));

        $participants = $registration->getParticipants();
        self::assertCount(1, $participants);
        $participant = $participants->current();
        self::assertInstanceOf(Participant::class, $participant);
        self::assertSame('FName ' . $key, $participant->getProperty('givenName'));
        self::assertSame('LName ' . $key, $participant->getProperty('familyName'));
        self::assertSame('c' . $key . '@gmail.com', $participant->getProperty('email'));
    }
}