<?php
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
/**
 * "Wraps" botman
 */

class CRM_Chat_Utils {

  static function shorten($string, $length = 50) {

    return strlen($string) > $length ? substr($string, 0, $length)."..." : $string;

  }

  static function exit($code = 0) {

    if ($code === 0) {
      echo 'OK';
      CRM_Utils_System::civiExit(0);
    } else {
      CRM_Utils_System::civiExit($code);
    }

  }

  static function getOngoingConversation($contactId) {
    try {
      $conversation = civicrm_api3('activity', 'getsingle', [
        'target_contact_id' => $contactId,
        'activity_type_id' => 'Conversation',
        'activity_status_id' => 'Ongoing'
      ]);
      return $conversation;
    } catch (Exception $e) {
      return null;
    }
  }

  static function getChatCount($contactId) {
    return civicrm_api3('Activity', 'getcount', [
        'contact_id' => $contactId,
        'activity_type_id' => 'Conversation'
      ])
    ;
  }

  static function generateToken($length = 24){
    return bin2hex(openssl_random_pseudo_bytes($length));
  }
}
