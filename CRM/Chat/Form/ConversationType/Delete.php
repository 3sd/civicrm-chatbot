<?php

use CRM_Chat_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */

class CRM_Chat_Form_ConversationType_Delete extends CRM_Chat_Form_Good_Delete {

  var $entities = [
    'ChatConversationType' => [
      'type' => 'ChatConversationType',
      'param' => 'id',
    ]
  ];

  var $deleteEntityText = 'conversation type';
  var $deleteEntityLabelField = 'name';

  function getGoodTitle(){
    return 'Delete conversation type';
  }

  function getContext() {
    return CRM_Utils_System::url('civicrm/chat/conversationType/view', 'id='.$this->entities['ChatConversationType']['before']['id']);
  }

  function getDestination() {
    return CRM_Utils_System::url('civicrm/chat/conversationType');
  }
}
