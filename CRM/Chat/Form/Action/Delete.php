<?php

use CRM_Chat_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */

class CRM_Chat_Form_Action_Delete extends CRM_Chat_Form_Good_Delete {

  function loadEntities(){

    $this->entities = [
      'ChatAction' => [
        'type' => 'ChatAction',
        'param' => 'id',
        'references' => [
          'question_id' => [
            'entity' => 'ChatQuestion',
            'field' => 'id'
          ]
        ]
      ],
      'ChatQuestion' => [
        'type' => 'ChatQuestion',
        'process' => false
      ]
    ];
  }

  var $deleteEntityText = 'action';

  function getGoodTitle(){
    return 'Delete action';
  }

  function getDescription(){
    $description = '';
    $check = unserialize($this->entities['ChatAction']['before']['check_object']);
    if(get_class($check) != 'CRM_Chat_Check_Anything'){
      $description .= 'if ' . $check->summarise() . ', ';
    }

    switch ($this->entities['ChatAction']['before']['type']) {
      case 'next':
        $question = civicrm_api3('ChatQuestion', 'getsingle', ['id' => $this->entities['ChatAction']['before']['action_data']]);
        $description .= "go to question '{$question['text']}'";
        break;

      case 'say':
        $description .= "say '{$this->entities['ChatAction']['before']['action_data']}'";
        break;

      case 'conversation':
        $conversationType = civicrm_api3('ChatConversationType', 'getsingle', ['id' => $this->entities['ChatAction']['before']['action_data']]);
        $description .= "start conversation '{$conversationType['name']}'";
        break;

      case 'group':
        $group = civicrm_api3('Group', 'getsingle', ['id' => $this->entities['ChatAction']['before']['action_data']]);
        $description .= "add to group '{$group['title']}'";
        break;

      case 'field':
        $description .= "add to field '{$this->entities['ChatAction']['before']['action_data']}'";
        break;
    }


    return $description;
  }

  function getContext() {
    return CRM_Utils_System::url('civicrm/chat/conversationType/view', 'id='.$this->entities['ChatQuestion']['before']['conversation_type_id']);
  }

  function getDestination() {
    return CRM_Utils_System::url('civicrm/chat/conversationType/view', 'id='.$this->entities['ChatQuestion']['before']['conversation_type_id']);
  }
}
