<?php
/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */

class CRM_Chat_Form_ConversationType_Edit extends CRM_Chat_Form_Good {

  function getGoodTitle(){
    return 'Edit Conversation type';
  }

  var $fields = [
    1 => [
      'entity' => 'ChatConversationType',
      'field' => 'name',
      'title' => 'Name',
      'required' => true,
      'help' => 'A descriptive name for the conversation type',
    ],
    2 => [
      'entity' => 'ChatConversationType',
      'field' => 'first_question_id',
      'title' => 'First question',
      'required' => true,
      'help' => 'The opening question of this conversation type',
    ],
    3 => [
      'entity' => 'ChatConversationType',
      'field' => 'timeout',
      'required' => true,
      'title' => 'Timeout',
      'help' => 'Time in minutes, after which this conversation type should be considered complete',
    ],
  ];

  var $entities = [
    'ChatConversationType' => [
      'type' => 'ChatConversationType',
      'param' => 'id',
    ]
  ];

  var $submitText = 'Save';

  function preProcess(){

    parent::preProcess();

    $this->fields[2]['entityref_api'] = [
      'label_field' => 'text',
      'search_field' => 'text',
      'params' => [
        'conversation_type_id' => $this->entities['ChatConversationType']['before']['id']
      ]
    ];

  }

  function getDestination() {
    return CRM_Utils_System::url('civicrm/chat/conversationType/view', 'id='.$this->entities['ChatConversationType']['before']['id']);
  }

  function getContext() {
    return CRM_Utils_System::url('civicrm/chat/conversationType/view', 'id='.$this->entities['ChatConversationType']['before']['id']);
  }

}
