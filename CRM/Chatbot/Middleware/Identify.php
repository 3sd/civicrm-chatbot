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

    // Get/Create a contact for this user and service
    $contactId = $this->getContactId($user, $service);
    $message->addExtras('contact_id', $contactId);

    // Add extra data to the contact made if the service provides any
    $extraInfoClass = 'addExtra' . ucfirst($service) . 'Info';
    if(method_exists($this,$extraInfoClass )){
      $this->$extraInfoClass($user, $contactId);
    }

    return $next($message);
  }

  // TODO Test with existent and non existent account
  function getContactId($user, $service){

    try {
      $chatUser = civicrm_api3('ChatUser', 'getsingle', [
        'service' => $service,
        'user_id' => $user->getId()
      ]);
      return $chatUser['contact_id'];
    } catch (Exception $e) {
      $contact = $this->createContact($user, $service);
    }
    return $contact['id'];
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

    return $contact['values'][$contact['id']];
  }

  function addExtraFacebookInfo($user, $contactId){
    //TODO Upload Facebook image
  }

}
