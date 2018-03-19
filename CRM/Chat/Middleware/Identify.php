<?php
use BotMan\BotMan\Interfaces\Middleware\Received;
use BotMan\BotMan\Interfaces\Middleware\Sending;
use BotMan\BotMan\Messages\Incoming\IncomingMessage;
use BotMan\BotMan\BotMan;

class CRM_Chat_Middleware_Identify implements Received, Sending {

  public function received(IncomingMessage $message, $next, BotMan $bot) {

    $service = CRM_Chat_Botman::shortName($bot->getDriver());
    $user = $bot->getDriver()->getUser($message);

    $this->identify($message, $service, $user);

    return $next($message);

  }

  // Used to identifiy server originated messages
  public function sending($payload, $next, BotMan $bot) {

    // The server fakes an incoming message from the user
    // Use this to identify the recipient
    $message = $bot->getMessage();
    $service = CRM_Chat_Botman::shortName($bot->getDriver());
    $user = $bot->getDriver()->getUser($message);
    $this->identify($message, $service, $user);

    return $next($payload);

  }

  function identify($message, $service, $user){

    try {
      $chatUser = civicrm_api3('ChatUser', 'getsingle', [
        'service' => $service,
        'user_id' => $user->getId()
      ]);
      $contactId = $chatUser['contact_id'];
    } catch (Exception $e) {
      $contactId = $this->createContact($user, $service);
    }

    $message->addExtras('contact_id', $contactId);

  }

  function createContact($user, $service){
    $contact = civicrm_api3('Contact', 'create', [
      'contact_type' => 'Individual',
      'source' => 'Chatbot',
      'first_name' => $user->getFirstName(),
      'last_name' => $user->getLastName()
    ]);

    $result = civicrm_api3('EntityTag', 'create', array(
      'contact_id' => $contact['id'],
      'tag_id' => "Chatbot"
    ));
    $result = civicrm_api3('ChatUser', 'create', [
      'contact_id' => $contact['id'],
      'service' => $service,
      'user_id' => $user->getId()
    ]);

    $extraInfoClass = "addExtra{$service}Info";
    if(method_exists($this, $extraInfoClass)){
      $this->$extraInfoClass($user, $contact['id']);
    }

    return $contact['id'];
  }

  function addExtraFacebookInfo($user, $contactId) {

    $info = $user->getInfo();

    // TODO upload image

    civicrm_api3('Contact', 'create', [
      'id' => $contactId,
      'image_URL' => $info['profile_pic'],
      'gender' => $info['gender']
    ]);

  }

}
