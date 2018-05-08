<?php
/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */

class CRM_Chat_Form_Action_Add extends CRM_Chat_Form_Good {

  var $submitText = 'Save';

  function initFields(){

    $matches = civicrm_api3('OptionValue', 'get', ['option_group_id' => 'chat_check_type']);

    $this->fields = [
      'ChatAction:match' => [
        'entity' => 'ChatAction',
        'field' => 'match',
        'title' => 'Match',
        'help' => 'The type of match that  that you would like asked next',
        'type' => 'select',
        'options' => array_column($matches['values'], 'label', 'value')
      ],
      'ChatAction:match_contains' => [
        'entity' => 'ChatAction',
        'field' => 'match_contains',
        'title' => 'Answer contains',
        'help' => 'Answers that contain the above text will be matched',
        'type' => 'text',
      ],
      'ChatAction:match_exactly' => [
        'entity' => 'ChatAction',
        'field' => 'match_equals',
        'title' => 'Answer is exactly',
        'help' => 'Answers that are exactly the above text will be matched',
        'type' => 'text',
      ],
      'ChatAction:type' => [
        'entity' => 'ChatAction',
        'field' => 'type',
        'title' => 'Type',
        'help' => 'The type of action',
        'type' => 'select',
        'options' => [
          'next' => 'Go to question',
          'say' => 'Say',
          'conversation' => 'Start conversation',
          'group' => 'Add to group',
          'field' => 'Add to field',
        ],
      ],
      'ChatAction:nextQuestion' => [
        'entity' => 'ChatAction',
        'field' => 'next',
        'title' => 'Next question',
        'help' => 'The question to ask next',
        'type' => 'EntityRef',
        'entityref_entity' => 'ChatQuestion',
      ],
      'ChatAction:say' => [
        'entity' => 'ChatAction',
        'field' => 'say',
        'title' => 'Say',
        'help' => 'The text you would like to reply with',
        'type' => 'text',
        'entityref_entity' => 'ChatQuestion',
      ],
      'ChatAction:startConversation' => [
        'entity' => 'ChatAction',
        'field' => 'conversation',
        'title' => 'Start conversation',
        'help' => 'The conversation type to start',
        'type' => 'EntityRef',
        'entityref_entity' => 'ChatConversationType',
      ],
      'ChatAction:addToGroup' => [
        'entity' => 'ChatAction',
        'field' => 'group',
        'title' => 'Add to group',
        'help' => 'The conversation type to start',
        'type' => 'EntityRef',
        'entityref_entity' => 'Group',
      ],
      'ChatAction:addToField' => [
        'entity' => 'ChatAction',
        'field' => 'field',
        'title' => 'Add to field',
        'help' => 'The conversation type to start',
        'type' => 'Select',
      ],
      'ChatAction:action_data' => [
        'entity' => 'ChatAction',
        'field' => 'action_data',
        'type' => 'hidden',
      ],
      'ChatAction:check_object' => [
        'entity' => 'ChatAction',
        'field' => 'check_object',
        'type' => 'hidden',
      ],
    ];
  }

  function initEntities(){

    $this->entities = [
      'ChatAction' => [
        'type' => 'ChatAction',
        'references' => [
          'question_id' => [
            'entity' => 'ChatQuestion',
            'field' => 'id'
          ]
        ]
      ],
      'ChatQuestion' => [
        'type' => 'ChatQuestion',
        'param' => 'questionId',
        'process' => false
      ]
    ];
  }

  function preProcessMassage(){
    $conversationType = civicrm_api3('ChatConversationType', 'getsingle', ['id' => $this->entities['ChatQuestion']['before']['conversation_type_id']]);
    $this->entities['ChatConversationType'] = [
      'before' => $conversationType,
      'process' => false
    ];

    $this->fields['ChatAction:nextQuestion']['entityref_api'] = [
      'label_field' => 'text',
      'search_field' => 'text',
      'params' => [
        'conversation_type_id' => $this->entities['ChatConversationType']['before']['id']
      ]
    ];
    $contactFields = array_column(civicrm_api3('Contact', 'getfields', ['action' => 'get'])['values'], 'title', 'name');
    unset($contactFields['id']);
    unset($contactFields['contact_type']);
    unset($contactFields['contact_sub_type']);
    $this->fields['ChatAction:addToField']['options'] = $contactFields;

    CRM_Core_Resources::singleton()->addScriptFile('chatbot', 'templates/CRM/Chat/Form/Action.js');

  }

  function buildQuickForm(){
    parent::buildQuickForm();
  }


  function getGoodTitle(){
    return 'Add action';
  }

  function getDestination() {
    return CRM_Utils_System::url('civicrm/chat/conversationType/view', 'id='.$this->entities['ChatConversationType']['before']['id']);
  }

  function getContext() {
    return CRM_Utils_System::url('civicrm/chat/conversationType/view', 'id='.$this->entities['ChatConversationType']['before']['id']);
  }

  function postProcessMassage($submitted){

    // Create serialized match object
    switch ($submitted['ChatAction:match']) {
      case 'CRM_Chat_Check_Anything':
        $check = new CRM_Chat_Check_Anything();
        break;

      case 'CRM_Chat_Check_Contains':
        $check = new CRM_Chat_Check_Contains(['contains' => $submitted['ChatAction:match_contains']]);
        break;

      case 'CRM_Chat_Check_Equals':
        $check = new CRM_Chat_Check_Equals(['equals'  => $submitted['ChatAction:match_equals']]);
        break;
    }

    $submitted['ChatAction:check_object'] = serialize($check);
    unset($submitted['ChatAction:match']);
    unset($submitted['ChatAction:match_contains']);
    unset($submitted['ChatAction:match_equals']);

    // Create action data
    switch ($submitted['ChatAction:type']) {
      case 'next':
        $submitted['ChatAction:action_data'] = $submitted['ChatAction:next'];
        break;

      case 'say':
        $submitted['ChatAction:action_data'] = $submitted['ChatAction:say'];
        break;

      case 'conversation':
        $submitted['ChatAction:action_data'] = $submitted['ChatAction:conversation'];
        break;

      case 'group':
        $submitted['ChatAction:action_data'] = $submitted['ChatAction:group'];
        break;

      case 'field':
        $submitted['ChatAction:action_data'] = $submitted['ChatAction:field'];
        break;
    }

    unset($submitted['ChatAction:next']);
    unset($submitted['ChatAction:say']);
    unset($submitted['ChatAction:conversation']);
    unset($submitted['ChatAction:group']);
    unset($submitted['ChatAction:field']);

    return $submitted;
  }


  function postProcess() {
    parent::postProcess();
  }

}
