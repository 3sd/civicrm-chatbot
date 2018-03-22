<?php
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\FacebookDriver;

// TODO refactor into bot/driver and listener

class CRM_Chat_Listen {

  static function create($driver) {

    $botman = CRM_Chat_Botman::get($driver);

    $botman->middleware->received(new CRM_Chat_Middleware_Identify());
    $botman->middleware->received(new CRM_Chat_Middleware_RecordIncoming());
    $botman->middleware->sending(new CRM_Chat_Middleware_RecordOutgoing());

    $botman->hears('(.*)', function ($bot, $hears) {
      $hear = new CRM_Chat_BAO_ChatHear;
      $hear->text = $hears;
      if($hear->find() == 1){
        $hear->fetch();
        CRM_Chat_Logger::debug($hear->chat_conversation_type_id);
        $bot->startConversation(new CRM_Chat_Conversation(CRM_Chat_BAO_ChatConversationType::findById($hear->chat_conversation_type_id)));
      }
    });

    $botman->listen();
    CRM_Chat_Utils::exit();

  }

}
