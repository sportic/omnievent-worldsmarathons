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
        $this->readFromArrayBaseData($data);
        $this->readFromArrayAddress($data['address']);
        $this->readFromArrayEmergencyContact($data['ice']);
        $this->readFromArrayAnswers($data['info']);

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

    protected function readFromArrayAddress(?array $addressData)
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

        $this->object->address($address);
    }

    /**
     * @param array $data
     * @return void
     */
    protected function readFromArrayBaseData(array $data): void
    {
        $this->object->givenName($data['first_name']);
        $this->object->familyName($data['last_name']);
        $this->object->gender($this->parseGender($data['gender']));
        $this->object->email($data['email']);
        $this->object->telephone($data['phone']['country_code'] . $data['phone']['number']);
        $this->object->birthDate($data['birth_date']);
        $this->object->nationality($data['nationality']);
        $this->object->clubByName($data['club']);
    }

    protected function readFromArrayEmergencyContact(mixed $data)
    {
        if (empty($data)) {
            return null;
        }
        $emergencyContact = EmergencyContactReader::from($data);
        $this->object->emergencyContact($emergencyContact);
    }

    protected function readFromArrayAnswers(array $data)
    {
        if (empty($data)) {
            return null;
        }
        $questions = RegistrationAnswersReader::from($data);
        $this->object->registrationAnswers($questions);
    }
}
