<?php

use CRM_Chat_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */

class CRM_Chat_Form_ConversationType_Add extends CRM_Chat_Form_Good {


  var $fields = [
    [
      'entity' => 'ChatConversationType',
      'field' => 'name',
      'title' => 'Conversation name',
      'help' => 'A descriptive name for the conversation',
    ],
    [
      'entity' => 'ChatQuestion',
      'field' => 'text',
      'title' => 'Opening question',
      'help' => 'The first question of this conversation',
    ],
    [
      'entity' => 'ChatConversationType',
      'field' => 'timeout',
      'title' => 'Timeout',
      'help' => 'Time in minutes, after which this conversation should be considered complete',
    ],
  ];

  var $entities = [
    'ChatConversationType' => [
      'type' => 'ChatConversationType',
    ],
    'ChatQuestion' => [
      'type' => 'ChatQuestion',
      'references' => [
        'conversation_type_id' => [
          'entity' => 'ChatConversationType',
          'field' => 'id'
          ]
      ]
    ]
  ];

  var $submitText = 'Add';


  function getGoodTitle(){
    return 'Add Conversation type';
  }

  function setDefaultValues(){

    $defaults['ChatConversationType:timeout'] = 30;
    return $defaults;

  }

  function getDestination() {
    return CRM_Utils_System::url('civicrm/chat/conversationType/view', 'id='.$this->entities['ChatConversationType']['after']['id']);
  }

  function getContext() {
    return CRM_Utils_System::url('civicrm/chat/conversationType');
  }


  function postProcess(){

    parent::postProcess();

    $params = [
      'id' => $this->entities['ChatConversationType']['after']['id'],
      'first_question_id' => $this->entities['ChatQuestion']['after']['id']
    ];

    $result = civicrm_api3('ChatConversationType', 'create', $params);

  }

}
