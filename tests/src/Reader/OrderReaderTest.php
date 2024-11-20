<?php

namespace Sportic\OmniEvent\Worldsmarathons\Tests\Reader;

use Spatie\SchemaOrg\Invoice;
use Spatie\SchemaOrg\OrderItem;
use Spatie\SchemaOrg\Organization;
use Spatie\SchemaOrg\PostalAddress;
use Sportic\OmniEvent\Models\Orders\RegistrationOrder;
use Sportic\OmniEvent\Models\Participants\EmergencyContact;
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
        self::assertInstanceOf(OrderItem::class, $orderItem);

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
        self::assertSame('421908888888' . $key, $participant->getProperty('telephone'));
        self::assertSame('SK' . $key, $participant->getProperty('nationality'));

        $club = $participant->getClub();
        self::assertInstanceOf(Organization::class, $club);
        self::assertSame('Club ' . $key, $club->getProperty('name'));

        $address = $participant->getProperty('address');
        self::assertInstanceOf(PostalAddress::class, $address);
        self::assertSame('Address ' . $key, $address->getProperty('streetAddress'));
        self::assertSame('City ' . $key, $address->getProperty('addressLocality'));
        self::assertSame('999' . $key, $address->getProperty('postalCode'));
        self::assertSame('State ' . $key, $address->getProperty('addressRegion'));
        self::assertSame('CT' . $key, $address->getProperty('addressCountry'));

        $emergencyContact = $participant->getEmergencyContact();
        self::assertInstanceOf(EmergencyContact::class, $emergencyContact);
        self::assertSame('EName ' . $key, $emergencyContact->getProperty('name'));
        self::assertSame('421909999999' . $key, $emergencyContact->getProperty('telephone'));

        $this->test_base_order_item_questions($participant, $key);
    }

    /**
     * @param Participant $participant
     * @param $key
     * @return void
     */
    protected function test_base_order_item_questions($participant, $key1): void
    {
        $answers = $participant->getRegistrationAnswers();
        self::assertCount(3, $answers);
        foreach ($answers as $key2 => $answer) {
            $key2++;
            $question = $answer->getQuestion();
            self::assertEquals('Question ' . $key1 . $key2, $question->getProperty('text'));
            self::assertEquals('Value ' . $key1 . $key2, $answer->getProperty('text'));
        }
    }
}