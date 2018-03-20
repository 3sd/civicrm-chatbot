<?php
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class CRM_Chat_Conversation extends Conversation
{

  public function __construct($conversationType){
    $this->conversationType = $conversationType;
  }

  public function run() {
    $this->askQuestion($this->conversationType->first_question_id);
  }

  public function askQuestion($questionId) {
    $question = CRM_Chat_BAO_ChatQuestion::findById($questionId);
    $this->ask($question->text, function(){});
  }
}
