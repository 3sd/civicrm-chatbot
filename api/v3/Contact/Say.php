<?php
use CRM_Chat_ExtensionUtil as E;

function civicrm_api3_contact_say($params) {

  $required = [
    'id',
    'service',
    'text'
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

  $botman->say($params['text'], $user['user_id'], CRM_Chat_Botman::getDriver($params['service']));

}
