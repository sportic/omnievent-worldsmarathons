<?php

namespace Sportic\OmniEvent\Worldsmarathons\Reader;

use Sportic\OmniEvent\Models\RegistrationQuestions\RegistrationAnswer;
use Sportic\OmniEvent\Models\RegistrationQuestions\RegistrationAnswersList;
use Sportic\OmniEvent\Models\RegistrationQuestions\RegistrationQuestion;

/**
 * @property RegistrationAnswersList $object
 */
class RegistrationAnswersReader extends AbstractReader
{

    public function readFromArray(array $data): ?self
    {
        foreach ($data as $answerData) {
            $this->object->append(
                $this->readFromArrayAnswer($answerData)
            );
        }
        return $this;
    }

    protected function resultObjectClass(): string
    {
        return RegistrationAnswersList::class;
    }

    protected function readFromArrayAnswer(mixed $answerData)
    {
        $question = new RegistrationQuestion();
        $question->text($answerData['label']);
        $question->identifierExternal($answerData['external_option_id']);
        $answer = new RegistrationAnswer();
        $answer->question($question);
        $answer->text($answerData['value']);
        $answer->identifierExternal($answerData['external_value_id']);
        return $answer;
    }
}