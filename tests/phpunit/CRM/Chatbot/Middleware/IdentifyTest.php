<?php

use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;
use BotMan\BotMan\Users\User;

/**
 * @group headless
 */
class CRM_Chat_Middleware_IdentifyTest extends CRM_Chat_Test implements HeadlessInterface, HookInterface, TransactionalInterface {

  function setUp(){
    $this->var = 'mikey';
  }

  function testGetContactIdWithNewUser() {

    $user = new User('id-not-in-civicrm', 'Sam', 'Beckett', 'sambeckett');

    $identifier = new CRM_Chat_Middleware_Identify;
    $contactId = $identifier->getContactId($user, 'chatservice');

    $contact = civicrm_api3('Contact', 'getsingle', ['id' => $contactId]);
    $chatUser = civicrm_api3('ChatUser', 'getsingle', ['contact_id' => $contactId]);

    self::assertEquals($contact['first_name'], $user->getFirstName());
    self::assertEquals($contact['last_name'], $user->getLastName());
    self::assertEquals($chatUser['user_id'], $user->getId());

  }

  function testGetContactIdWithExistingUser() {

    $user = new User('id-already-in-civicrm', 'Al', 'Calavicci', 'alcalavicci');

    // Set up the Contact and ChatUser before testing the function
    $contact = civicrm_api3('Contact', 'create', [
      'contact_type' => 'Individual',
      'first_name' => $user->getFirstName(),
      'last_name' => $user->getLastName()
    ]);
    $chatUser = civicrm_api3('ChatUser', 'create', [
      'contact_id' => $contact['id'],
      'service' => 'chatservice',
      'user_id' => $user->getId()
    ]);

    $identifier = new CRM_Chat_Middleware_Identify;
    $contactId = $identifier->getContactId($user, 'chatservice');
    self::assertEquals($contact['id'], $contactId);

  }

}
