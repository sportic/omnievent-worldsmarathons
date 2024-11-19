<?php

namespace Sportic\OmniEvent\Worldsmarathons\Reader;

use Spatie\SchemaOrg\PostalAddress;
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
        $this->object->gender($this->parseGender($data['gender']));
        $this->object->email($data['email']);
        $this->object->telephone($data['phone']['country_code'] . $data['phone']['number']);

        $this->object->birthDate($data['birth_date']);
        $this->object->nationality($data['nationality']);
        $this->object->clubByName($data['club']);
        $this->object->address($this->parseAddress($data['address']));

//        var_dump($data['info']);

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

    protected function parseAddress(?array $addressData): ?PostalAddress
    {
        if (empty($addressData)) {
            return null;
        }
        $address = new PostalAddress();
        $address->streetAddress($addressData['address_line_1']?? null);
        $address->postalCode($addressData['zip'] ?? null);
        $address->addressLocality($addressData['city'] ?? null);
        $address->addressRegion($addressData['state'] ?? null);
        $address->addressCountry($addressData['country_code'] ?? null);
        return $address;
    }
}