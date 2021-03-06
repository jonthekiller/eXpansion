<?php

namespace ManiaLivePlugins\eXpansion\Quiz\Structures;

class Question {

    const Correct = 0x01;
    const MoreAnswersNeeded = 0x02;
    const WrongAnswer = 0x04;

    /** @var string $challenge the actual question */
    public $question;

    /** @var Answer[] answer */
    public $answer = array();

    /** @var \DedicatedApi\Structures\Player */
    public $asker;

    /** var float */
    public $pointsValue = 1.0;

    /** @var bool */
    public $multipart = false;

    public function __construct(\DedicatedApi\Structures\Player $player, $question) {
        $this->asker = $player;
        $this->question = $question;
        return $this;
    }

    /**
     * setQuestion($question)
     * Setter for question in place, can be used to correct typos 
     *
     * @param string $question
     */
    public function setQuestion($question) {
        $this->question = $question;
        return $this;
    }

    /**
     * getQuestion()
     * Getter for question
     * @return string
     */
    public function getQuestion() {
        return $this->question;
    }

    /**
     * addAnswer()
     * adds a correct answer
     * @param \ManiaLivePlugins\eXpansion\Quiz\Structures\Answer $answer
     */
    public function addAnswer($answer) {
        $this->answer[] = new Answer($answer);
    }

    /**
     * removeAnswer()
     * removes a correct answer
     * @param integer $index;
     */
    public function removeAnswer($index) {
        try {
            unset($this->answer[$index]);
        } catch (\Exception $e) {
            
        }
        return $this;
    }

    /**
     * setAnswer($index, $answer);
     * setter for answer
     * @param int $index
     * @param \ManiaLivePlugins\eXpansion\Quiz\Structures\Answer $answer
     */
    public function setAnswer($index, Answer $answer) {
        $this->answer[$index] = $answer;
    }

    public function getAnswers() {
        return $this->answer;
    }

    /**
     * checkAnswer($message);
     * @param string $message
     * @return boolean
     */
    public function checkAnswer($message) {
        foreach ($this->answer as $answer) {
            if ($answer->used)
                continue;

            if (mb_strtolower($answer->answer, 'UTF-8') === mb_strtolower($message, 'UTF-8')) {
                $answer->used = true;
                if ($this->multipart) {
                    return self::MoreAnswersNeeded;
                }
                return self::Correct;
            }
        }
        return self::WrongAnswer;
    }

}

?>
