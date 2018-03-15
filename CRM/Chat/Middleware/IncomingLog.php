<?php
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\BotMan;

class CRM_Chat_Middleware_IncomingLog implements Received {

  public function received(IncomingMessage $message, $next, BotMan $bot) {
    CRM_Chat_Logger::debug("Message <- contact_id ".$message->getExtras('contact_id') . ':' . $message->getText());
    return $next($message);
  }


}
