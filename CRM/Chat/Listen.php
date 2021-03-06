<?php
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use BotMan\Drivers\Facebook\FacebookDriver;

class CRM_Chat_Listen {

  static function create($driver, $verifyMode = false) {

    $botman = CRM_Chat_Botman::get($driver);

    if(!$verifyMode){

      $botman->middleware->received(new CRM_Chat_Middleware_Identify());
      $botman->middleware->received(new CRM_Chat_Middleware_RecordIncoming());
      $botman->middleware->sending(new CRM_Chat_Middleware_RecordOutgoing());

      $botman->hears('(.*)', function ($bot, $hears) {

        $hear = new CRM_Chat_BAO_ChatHear;
        $hear->text = $hears;
        if($hear->find() == 1){
          $hear->fetch();
          civicrm_api3('Contact', 'start_conversation', [
            'id' => $bot->getMessage()->getExtras('contact_id'),
            'source_contact_id' => $bot->getMessage()->getExtras('contact_id'),
            'service' => CRM_Chat_Driver::getServiceName($bot->getDriver()),
            'conversation_type_id' => CRM_Chat_BAO_ChatConversationType::findById($hear->chat_conversation_type_id)->id
          ]);
        }
      });
    }

    $botman->listen();

  }

}
