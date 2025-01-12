<?php

namespace Sportic\OmniEvent\Worldsmarathons\Reader;

use Sportic\OmniEvent\Worldsmarathons\Dto\CallbackEvent;

/**
 * @property CallbackEvent $object
 * @method CallbackEvent from($json)
 */
class CallbackEventReader extends AbstractReader
{
    public function readFromArray(array $data): ?self
    {
        $this->object->id = $data['id'];
        $this->object->created = $data['created'];
        $this->object->type = $data['type'];
        $order = OrderReader::fromArray($data['data']);
        $this->object->order = $order;
        return $this;
    }

    protected function resultObjectClass(): string
    {
        return CallbackEvent::class;
    }
}