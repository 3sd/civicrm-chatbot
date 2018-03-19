<?php
use CRM_Chat_ExtensionUtil as E;

function civicrm_api3_contact_start_conversation($params) {

  $required = [
    'id',
    'service',
    'conversation_type_id'
  ];

  $missingFields = array_diff($required, array_keys($params));

  if(count($missingFields)){
    throw new API_Exception('Mandatory key(s) missing from params array: ' . implode(', ', $missingFields));
  }

  try {
    $user = civicrm_api3('ChatUser', 'getsingle', [
      'service' => $params['service'],
      'contact_id' => $params['id']
    ]);
  } catch (\Exception $e) {
    throw new API_Exception("Could not find {$params['service']} user for contact_id {$params['id']}");
  }

  $botman = CRM_Chat_Botman::getBot($params['service']);

  $botman->middleware->sending(new CRM_Chat_Middleware_Identify());
  $botman->middleware->sending(new CRM_Chat_Middleware_RecordOutgoing());

  $botman->startConversation(new CRM_Chat_Conversation($params['conversation_type_id']), $user['user_id'], CRM_Chat_Botman::getDriver($params['service']));

  return civicrm_api3_create_success();

}
