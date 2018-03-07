<?php
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class CRM_Chat_Conversation extends Conversation
{
  protected $firstname;

  protected $email;


  public function __construct($id){
    $this->id = $id;
  }

  public function run()
  {
    $this->askFirstname();
  }
}
