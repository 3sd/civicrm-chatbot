<?php
class CRM_Chat_Form_Start extends CRM_Chat_Form_Good{

  function getDestination() {
    return CRM_Utils_System::url('civicrm/contact/view', 'reset=1&cid='.$this->entities['Contact']['before']['id']);
  }

  function getContext() {
    return CRM_Utils_System::url('civicrm/contact/view', 'reset=1&cid='.$this->entities['Contact']['before']['id']);
  }

  function getGoodTitle() {
    return 'Start a conversation with '.$this->entities['Contact']['before']['display_name'];
  }

  function initEntities(){
    $this->entities = [
      'Contact' => [
        'type' => 'Contact',
        'param' => 'cid',
        'process' => false
      ]
    ];
  }

  function initFields(){

    $users = civicrm_api3('ChatUser', 'get', ['contact_id' => $this->entities['Contact']['before']['id']])['values'];

    $this->fields = [
      'Conversation:conversationTypeId' => [
        'entity' => 'Conversation',
        'field' => 'conversationTypeId',
        'title' => 'Conversation type',
        'type' => 'entityref',
        'entityref_entity' => 'ChatConversationType',
      ],
      'Conversation:ChatService' => [
        'entity' => 'Conversation',
        'field' => 'ChatService',
        'title' => 'Chat service',
        'type' => 'select',
        'options' => array_column($users, 'service', 'service')
      ]
    ];

  }

  public function postProcess() {

    $values = $this->exportValues();

    $session = CRM_Core_Session::singleton();

    $params = [
      'id' => $this->entities['Contact']['before']['id'],
      'service' => $values['Conversation:ChatService'],
      'conversation_type_id' => $values['Conversation:conversationTypeId']
    ];
    $result = civicrm_api3('Contact', 'start_conversation', $params);
    CRM_Core_Session::setStatus(ts('Chat started with %1', [1 => $this->entities['Contact']['before']['display_name']]));
    parent::postProcess();
  }
}
