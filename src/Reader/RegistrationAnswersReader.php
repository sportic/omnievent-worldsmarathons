<?php

namespace Sportic\OmniEvent\Worldsmarathons\Reader;

use Sportic\OmniEvent\Models\RegistrationQuestions\RegistrationAnswersList;

class RegistrationAnswersReader extends AbstractReader
{

    public function readFromArray(array $data): ?self
    {
        // TODO: Implement readFromArray() method.
    }

    protected function resultObjectClass(): string
    {
        return RegistrationAnswersList::class;
    }
}