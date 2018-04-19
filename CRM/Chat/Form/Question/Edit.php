<?php
use CRM_Chat_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */

class CRM_Chat_Form_Question_Edit extends CRM_Chat_Form_Good {

  var $fields = [
    1 => [
      'entity' => 'ChatQuestion',
      'field' => 'text',
      'title' => 'Question',
      'help' => 'The text of the question',
    ]
  ];

  var $entities = [
    'ChatQuestion' => [
      'type' => 'ChatQuestion',
      'param' => 'id',
    ]
  ];

  var $submitText = 'Save';

  function getDelete() {
    return [
      'path' => 'civicrm/chat/question/delete',
      'query' => 'id='.$this->entities['ChatQuestion']['before']['id']
    ];
  }

  function getGoodTitle(){
    return E::ts('Edit question: ').$this->entities['ChatQuestion']['before']['text'];
  }

  function getDestination() {
    return CRM_Utils_System::url('civicrm/chat/conversationType/view', 'id='.$this->entities['ChatQuestion']['before']['conversation_type_id']);
  }

  function getContext() {
    return CRM_Utils_System::url('civicrm/chat/conversationType/view', 'id='.$this->entities['ChatQuestion']['before']['conversation_type_id']);
  }

}
