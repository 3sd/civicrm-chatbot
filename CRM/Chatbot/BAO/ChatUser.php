<?php
use CRM_Chatbot_ExtensionUtil as E;

class CRM_Chatbot_BAO_ChatUser extends CRM_Chatbot_DAO_ChatUser {

  /**
   * Create a new ChatUser based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Chatbot_DAO_ChatUser|NULL
   *
  public static function create($params) {
    $className = 'CRM_Chatbot_DAO_ChatUser';
    $entityName = 'ChatUser';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
