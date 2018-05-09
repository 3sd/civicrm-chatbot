<?php
use CRM_Chat_ExtensionUtil as E;

function civicrm_api3_contact_start_conversation($params) {

  // require fields
  $required = [
    'id',
    'service',
    'conversation_type_id'
  ];

  $missingFields = array_diff($required, array_keys($params));

  if(count($missingFields)){
    throw new API_Exception('Mandatory key(s) missing from params array: ' . implode(', ', $missingFields));
  }

  // Check user has account with service
  try {
    $user = civicrm_api3('ChatUser', 'getsingle', [
      'service' => $params['service'],
      'contact_id' => $params['id']
    ]);
  } catch (\Exception $e) {
    throw new API_Exception("Could not find {$params['service']} user for contact_id {$params['id']}");
  }

  $conversationType = CRM_Chat_BAO_ChatConversationType::findById($params['conversation_type_id']);

  $conversationActivityParams = [
    'target_contact_id' => $params['id'],
    'activity_type_id' => 'Conversation',
    'activity_status_id' => 'Ongoing'
  ];

  $conversationActivityParams['subject'] = $conversationActivityParams['subject'] = $params['service'] . ': ' . CRM_Chat_Utils::shorten($conversationType->name, 50);
  $conversationActivityParams['details'] = json_encode([
    'service' => $params['service'],
    'conversation_type_id' => $params['conversation_type_id']
  ]);

  if(isset($params['source_contact_id'])) {
    $conversationActivityParams['source_contact_id'] = $params['source_contact_id'];
  }

  $ongoingConversationCount = civicrm_api3('activity', 'getcount', $conversationActivityParams);

  if($ongoingConversationCount){
    $conversationActivityParams['activity_status_id'] = 'Scheduled';
    $conversation = civicrm_api3('activity', 'create', $conversationActivityParams);
    return civicrm_api3_create_success();
  }else{
    $conversation = civicrm_api3('activity', 'create', $conversationActivityParams);

    $botman = CRM_Chat_Botman::get($params['service']);
    $botman->middleware->sending(new CRM_Chat_Middleware_Identify());
    $botman->middleware->sending(new CRM_Chat_Middleware_RecordOutgoing());
    $botman->startConversation(
      new CRM_Chat_Conversation(
        $conversationType,
        $params['id']
      ),
      $user['user_id'],
      CRM_Chat_Botman::getDriver($params['service'])
    );

    return civicrm_api3_create_success();
  }
}
