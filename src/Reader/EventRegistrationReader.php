<?php

namespace Sportic\OmniEvent\Worldsmarathons\Reader;

use Sportic\OmniEvent\Models\Participants\Participant;
use Sportic\OmniEvent\Models\Races\Race;
use Sportic\OmniEvent\Models\Registrations\EventRegistration;

/**
 * @property EventRegistration $object
 */
class EventRegistrationReader extends AbstractReader
{

    public function readFromArray(array $data): ?self
    {
        $this->object->identifier($data['id']);

        $tickets = $data['tickets'];
        $ticketData = current($tickets);

        $race = $this->generateRace($ticketData);
        $this->object->reservationFor($race);

        $this->object->totalPrice($ticketData['price']);

        $participant = $this->generateParticipant($data);
        $this->object->addParticipant($participant);
        return $this;
    }

    protected function resultObjectClass(): string
    {
        return EventRegistration::class;
    }

    /**
     * @param array $data
     * @return \Spatie\SchemaOrg\BaseType|null
     * @throws \Exception
     */
    protected function generateParticipant(array $data): ?Participant
    {
        $participant = ParticipantReader::from($data);
        return $participant;
    }

    protected function generateRace(array $data): ?Race
    {
        $race = RaceReader::from($data);
        return $race;
    }
}
