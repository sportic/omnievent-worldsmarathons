<?php

namespace Sportic\OmniEvent\Worldsmarathons\Reader;

use Sportic\OmniEvent\Models\Orders\RegistrationOrder;

class OrderReader extends AbstractReader
{

    public function readFromArray(array $data): ?self
    {
        $this->object->orderNumber($data['order_reference']);
        $this->object->orderDate($data['order_date']);

        $this->readInvoice($data);
        $this->readParticipants($data['participants']);
        return $this;
    }

    protected function readInvoice($data)
    {
        $dataInvoice = [
            'amount' => $data['amount'],
            'currency' => $data['currency'],
        ];
        $this->object->partOfInvoice(
            InvoiceReader::from($dataInvoice)->result()
        );
    }

    protected function resultObjectClass(): string
    {
        return RegistrationOrder::class;
    }

    protected function readParticipants(mixed $participants): void
    {
        $items = [];
        foreach ($participants as $participant) {
            $items[] = OrderItemReader::from($participant)->result();
        }
        $this->object->orderedItem($items);
    }
}