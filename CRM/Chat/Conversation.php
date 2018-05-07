<?php
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class CRM_Chat_Conversation extends Conversation {

  public function __construct($conversationType) {

    $this->conversationType = $conversationType;

  }

  public function run() {

    $this->contactId = $this->getBot()->getMessage()->getExtras('contact_id');
    $this->askQuestion($this->conversationType->first_question_id);

  }

  protected function askQuestion($questionId) {

    $question = CRM_Chat_BAO_ChatQuestion::findById($questionId);

    $text = $this->tokenReplacement($question->text, $this->contactId);// TODO contact token replacement

    $this->ask($text, $this->answer($questionId));
    return;

  }

  protected function answer($questionId) {

    return function(Answer $answer) use ($questionId) {

      $actions = [
        'group' => function($groupId){

          civicrm_api3('GroupContact', 'create', [
            'contact_id' => $this->contactId,
            'group_id' => $groupId
          ]);

        },

        'field' => function($field, $value){

          civicrm_api3('Contact', 'create', ['id' => $this->contactId, $field => $value]);

        },

        'say' => function($text){

          $this->say($text);

        },

        'conversation' => function($conversationTypeId){

          $conversationType = CRM_Chat_BAO_ChatConversationType::findById($conversationTypeId);
          $this->bot->startConversation(new CRM_Chat_Conversation($conversationType));

        },

        'next' => function($questionId){

          $question = CRM_Chat_BAO_ChatQuestion::findById($questionId);
          $this->ask($question->text, $this->answer($questionId));

        },
      ];

      foreach($actions as $type => $closure) {
        $this->processAction($type, $answer->getText(), $questionId, $closure);
      }

    };

  }

  protected function processAction($type, $text, $questionId, $closure) {

    $action = CRM_Chat_BAO_ChatAction::findByTypeAndQuestion($type, $questionId);

    while($action->fetch()){
      $check = unserialize($action->check_object);
      if($check->matches($text)){

        // TODO if $type == 'next' then break out of while since we can only go to one place.
        // TODO add weight to 'next' actions so that they are executed in order

        $closure($action->action_data, $check->getMatch());

      }

    }

  }

  protected function tokenReplacement($text, $contactId) {

    //TODO token replacement

    return $text;

  }

}
