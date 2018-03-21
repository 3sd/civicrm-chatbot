<?php
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class CRM_Chat_Conversation extends Conversation
{

  public function __construct($conversationType) {

    $this->conversationType = $conversationType;

  }

  public function run() {

    $this->contactId = $this->getBot()->getMessage()->getExtras('contact_id');
    $this->askQuestion($this->conversationType->first_question_id);

  }

  protected function askQuestion($questionId) {

    $question = CRM_Chat_BAO_ChatQuestion::findById($questionId);
    $this->ask($question->text, $this->answer($questionId));
    return;

  }

  protected function answer($questionId) {

    return function(Answer $answer) use ($questionId) {

      $actions = [
        'group' => function($action){

        },
        'field' => function($action){

        },
        'trigger' => function($action){

        },
        'conversation' => function($action){

        },
        'next' => function($action){
          $question = CRM_Chat_BAO_ChatQuestion::findById($action);
          $this->ask($question->text, $this->answer($action));
        },
      ];

      foreach($actions as $type => $closure) {
        $this->processAction($answer->getText(), $questionId, $type, $closure);
      }

    };

  }

  protected function processAction($text, $questionId, $type, $closure) {
    $groups = CRM_Chat_BAO_ChatAction::findByTypeAndQuestion($type, $questionId);

    while($groups->fetch()){
      $check = unserialize($groups->check_object);
      if($check->matches($text)){
        CRM_Chat_Logger::debug('mikey');
        $closure($groups->action);
      }

    }

  }

}
