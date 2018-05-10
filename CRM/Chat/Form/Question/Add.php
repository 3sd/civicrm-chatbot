<?php
/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */

class CRM_Chat_Form_Question_Add extends CRM_Chat_Form_Good {

  var $fields = [
    1 => [
      'entity' => 'ChatQuestion',
      'field' => 'text',
      'title' => 'Question',
      'required' => true,
      'help' => 'The text of the question',
    ]
  ];

  var $entities = [
    'ChatQuestion' => [
      'type' => 'ChatQuestion',
      'references' => [
        'conversation_type_id' => [
          'entity' => 'ChatConversationType',
          'field' => 'id'
        ]
      ]
    ],
    'ChatConversationType' => [
      'type' => 'ChatConversationType',
      'param' => 'conversationTypeId'
    ]
  ];

  var $submitText = 'Save';

  function getGoodTitle(){
    return 'Add question';
  }

  function getDestination() {
    return CRM_Utils_System::url('civicrm/chat/conversationType/view', 'id='.$this->entities['ChatConversationType']['before']['id']);
  }

  function getContext() {
    return CRM_Utils_System::url('civicrm/chat/conversationType/view', 'id='.$this->entities['ChatConversationType']['before']['id']);
  }

}
