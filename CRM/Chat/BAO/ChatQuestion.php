<?php
use CRM_Chat_ExtensionUtil as E;

class CRM_Chat_BAO_ChatQuestion extends CRM_Chat_DAO_ChatQuestion {

  /**
   * Create a new ChatQuestion based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Chat_DAO_ChatQuestion|NULL
   *
  public static function create($params) {
    $className = 'CRM_Chat_DAO_ChatQuestion';
    $entityName = 'ChatQuestion';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
