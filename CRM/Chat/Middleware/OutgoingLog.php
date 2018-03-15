<?php
use BotMan\BotMan\Interfaces\Middleware\Sending;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\BotMan;

class CRM_Chat_Middleware_OutgoingLog implements Sending {


  public function sending($payload, $next, BotMan $bot)
  {
    CRM_Chat_Logger::debug("Message -> contact_id ".$bot->getMessage()->getExtras('contact_id') . ':' . $payload['message']['text']);
    return $next($payload);
  }

}
