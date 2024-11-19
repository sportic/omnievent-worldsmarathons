<?php

namespace Sportic\OmniEvent\Worldsmarathons\Reader;

use Sportic\OmniEvent\Models\Participants\Participant;

/**
 * @property Participant $object
 */
class ParticipantReader extends AbstractReader
{

    public function readFromArray(array $data): ?self
    {
        $this->object->givenName($data['first_name']);
        $this->object->familyName($data['last_name']);
        $this->object->email($data['email']);
        $this->object->telephone($data['phone']['country_code'] . $data['phone']['number']);
        $this->object->birthDate($data['birth_date']);
        $this->object->gender($this->parseGender($data['gender']));
        return $this;
    }

    protected function resultObjectClass(): string
    {
        return Participant::class;
    }

    protected function parseGender(mixed $gender)
    {
        switch ($gender) {
            case 'M':
                return 'Male';
            case 'F':
                return 'Female';
        }
        return null;
    }
}