<?php

use CRM_Chat_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */

class CRM_Chat_Form_Question_Delete extends CRM_Chat_Form_Good_Delete {

  var $entities = [
    'ChatQuestion' => [
      'type' => 'ChatQuestion',
      'param' => 'id',
    ]
  ];

  var $deleteEntityText = 'question';
  var $deleteEntityLabelField = 'text';

  function getGoodTitle(){
    return 'Delete question';
  }

  function getContext() {
    return CRM_Utils_System::url('civicrm/chat/conversationType/view', 'id='.$this->entities['ChatQuestion']['before']['conversation_type_id']);
  }

  function getDestination() {
    return CRM_Utils_System::url('civicrm/chat/conversationType/view', 'id='.$this->entities['ChatQuestion']['before']['conversation_type_id']);
  }
}
