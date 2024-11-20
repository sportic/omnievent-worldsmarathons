<?php

namespace Sportic\OmniEvent\Worldsmarathons\Reader;

use Sportic\OmniEvent\Models\Participants\EmergencyContact;

/**
 * @property EmergencyContact $object
 * @method static EmergencyContact from($data)
 */
class EmergencyContactReader extends AbstractReader
{

    public function readFromArray(array $data): ?self
    {
        $this->object->name($data['full_name'] ?? null);
        $this->object->telephone($data['country_code'] . $data['number'] ?? null);
        return $this;
    }

    protected function resultObjectClass(): string
    {
        return EmergencyContact::class;
    }
}