<?php
use CRM_Chat_ExtensionUtil as E;

class CRM_Chat_BAO_ChatConversationType extends CRM_Chat_DAO_ChatConversationType {

  /**
   * Create a new ChatConversationType based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Chat_DAO_ChatConversationType|NULL
   *
  public static function create($params) {
    $className = 'CRM_Chat_DAO_ChatConversationType';
    $entityName = 'ChatConversationType';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
