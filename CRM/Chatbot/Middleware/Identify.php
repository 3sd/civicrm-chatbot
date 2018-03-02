<?php
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\BotMan;

class CRM_Chatbot_Middleware_Identify implements Received {

  var $services = [
    'BotMan\Drivers\Facebook\FacebookDriver' => 'facebook'
  ];

  public function received(IncomingMessage $message, $next, BotMan $bot) {

    $user = $bot->getDriver()->getUser($message);
    $service = $this->services[get_class($bot->getDriver())];

    // Try to get a contact with the $userId and create one if you can't
    try {
      $contact = civicrm_api3('ChatUser', 'getsingle', [
        'service' => 'facebook',
        'user_id' => $user->getId()
      ]);
    } catch (Exception $e) {
      $contact = $this->createContact($user, $service);
    }

    $message->addExtras('contact_id', $contact['id']);

    // Add extra data to the contact made available by the service
    $extraInfoClass = 'addExtra' . ucfirst($service) . 'Info';

    if(method_exists($this,$extraInfoClass )){
      $this->$extraInfoClass($user, $contactId);
    }

    return $next($message);
  }

  function createContact($user, $service){
    $contact = civicrm_api3('Contact', 'create', [
      'contact_type' => 'Individual',
      'first_name' => $user->getFirstName(),
      'last_name' => $user->getLastName()
    ]);

    $result = civicrm_api3('ChatUser', 'create', [
      'contact_id' => $contact['id'],
      'service' => $service,
      'user_id' => $user->getId()
    ]);

    // TODO add contact to dedupe group
    //
    return $contact['values'][$contact['id']];
  }

  function addExtraFacebookInfo($user, $contactId){
    //TODO Upload Facebook image
  }

}
