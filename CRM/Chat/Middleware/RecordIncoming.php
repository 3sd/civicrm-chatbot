<?php
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\BotMan;

class CRM_Chat_Middleware_RecordIncoming extends CRM_Chat_Middleware implements Received {

  public function received(IncomingMessage $message, $next, BotMan $bot) {

    $details = $message->getText();
    $subject = CRM_Chat_Botman::shortName($bot->getDriver()) . ': ' . CRM_Chat_Utils::shorten($details, 50);
    $contactId = $message->getExtras('contact_id');



    civicrm_api3('activity', 'create', [
      'activity_type_id' => 'Incoming chat',
      'subject' => $subject,
      'details' => $details,
      'target_contact_id' => $contactId,
      'source_contact_id' => $contactId,
      'parent_id' => CRM_Chat_Utils::getOngoingConversation($contactId)['id']
    ]);

    return $next($message);
  }

}
