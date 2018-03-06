<?php
use BotMan\BotMan\Interfaces\Middleware\Sending;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\BotMan;

class CRM_Chatbot_Middleware_OutgoingLog implements Sending {


  public function sending($payload, $next, BotMan $bot)
  {
    CRM_Chatbot_Logger::log("Message -> contact_id ".$bot->getMessage()->getExtras('contact_id') . ':' . $payload['message']['text']);
    return $next($payload);
  }

}
