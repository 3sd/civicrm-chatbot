<?php
use BotMan\BotMan\Interfaces\Middleware\Sending;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\BotMan;

class CRM_Chat_Middleware_RecordOutgoing extends CRM_Chat_Middleware implements Sending {

  public function sending($payload, $next, BotMan $bot) {

    $details = $payload['message']['text'];
    $subject = CRM_Chat_Driver::getServiceName($bot->getDriver()) . ': ' . CRM_Chat_Utils::shorten($details, 50);
    $contactId = $bot->getMessage()->getExtras('contact_id');
    $conversationId = $bot->getMessage()->getExtras('conversation_id');

    civicrm_api3('activity', 'create', [
      'activity_type_id' => 'Outgoing chat',
      'subject' => $subject,
      'details' => $details,
      'target_contact_id' => $contactId,
      'source_contact_id' => $contactId,
      'parent_id' => CRM_Chat_Utils::getOngoingConversation($contactId)['id']
    ]);

    return $next($payload);
  }

}
