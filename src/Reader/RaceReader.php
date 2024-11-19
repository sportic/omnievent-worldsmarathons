<?php

namespace Sportic\OmniEvent\Worldsmarathons\Reader;

use Sportic\OmniEvent\Models\Races\Race;

/**
 * @property Race $object
 */
class RaceReader extends AbstractReader
{

    public function readFromArray(array $data): ?self
    {
        $this->object->identifier($data['product_id']);
        $this->object->name($data['product_name']);
        $this->object->identifierExternal($data['external_product_id']);
        return $this;
    }

    protected function resultObjectClass(): string
    {
        return Race::class;
    }
}