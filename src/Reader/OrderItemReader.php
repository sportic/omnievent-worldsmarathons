<?php

namespace Sportic\OmniEvent\Worldsmarathons\Reader;

class OrderItemReader extends AbstractReader
{

    public function readFromArray(array $data): ?self
    {
        $eventRegistration = EventRegistrationReader::from($data);
        $this->object->orderedItem($eventRegistration);
        return $this;
    }

    protected function resultObjectClass(): string
    {
        return \Spatie\SchemaOrg\OrderItem::class;
    }
}