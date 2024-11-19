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
        $race = $this->generateRace($data);
        $this->object->reservationFor($race);

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
        $tickets = $data['tickets'];
        $ticket = current($tickets);
        $race = RaceReader::from($ticket);
        return $race;
    }
}
