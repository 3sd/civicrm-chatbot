<?php

//Delete all existing entities

$entities = [
  'CRM_Chat_DAO_ChatCache',
  'CRM_Chat_DAO_ChatConversationType',
  'CRM_Chat_DAO_ChatHear',
  'CRM_Chat_DAO_ChatUser'
];
foreach($entities as $entity){
  echo "Deleting all {$entity} entities\n";
  $entity = new $entity;
  $entity->whereAdd('id > 0');
  $entity->delete(DB_DATAOBJECT_WHEREADD_ONLY);
}

$entities['ChatConversationType'] = [
  'pets' => [
    'name' => 'Cats or dogs?',
    'timeout' => '30'
  ],
  'fullName' => [
    'name' => 'Full name',
    'timeout' => '30'
  ],
];

$entities['ChatHear'] = [
  'petsurvey' => [
    'text' => 'pet survey',
    'chat_conversation_type_id' => '{{ChatConversationType.pets}}'
  ]
];

$entities['ChatQuestion'] = [
  'dogorcat' => [
    'conversation_type_id' => '{{ChatConversationType.pets}}',
    'text' => 'Are you a dog person or a cat person?',
  ],
  'dog' => [
    'conversation_type_id' => '{{ChatConversationType.pets}}',
    'text' => 'Do you have a dog?',
  ],
  'cat' => [
    'conversation_type_id' => '{{ChatConversationType.pets}}',
    'text' => 'Do you have a cat?',
  ],
  'catsName' => [
    'conversation_type_id' => '{{ChatConversationType.pets}}',
    'text' => 'What is your cats name?',
  ],
  'dogNewsletter' => [
    'conversation_type_id' => '{{ChatConversationType.pets}}',
    'text' => 'Would you like to join our newsletter for Dog owners?'
  ],
  'firstName' => [
    'conversation_type_id' => '{{ChatConversationType.fullName}}',
    'text' => 'What is your first name?',
  ],
  'lastName' => [
    'conversation_type_id' => '{{ChatConversationType.fullName}}',
    'text' => 'What is your last name?',
  ],
];

$created = [];
while(createEntities($entities, $created));
var_dump($created);
var_dump(civicrm_api3('ChatConversationType', 'create', [
  'id' => $created['ChatConversationType']['pets'],
  'first_question_id' => $created['ChatQuestion']['dogorcat']
]));

var_dump(civicrm_api3('ChatConversationType', 'create', [
  'id' => $created['ChatConversationType']['fullName'],
  'first_question_id' => $created['ChatQuestion']['firstName']
]));


function createEntities(&$entities, &$created){
  foreach($entities as $type => $objects){
    foreach($objects as $tag => $params){
      echo "$type.$tag\n";
      if(substitute($params, $created)){
        $result = civicrm_api3($type, 'create', $params);
        $created[$type][$tag] = $result['id'];
        unset($entities[$type][$tag]);
      }
    }
    if(count($entities[$type] == 0)){
      unset($entities[$type]);
    }
  }
  return count($entities);
}

function substitute(&$params, $created){
  foreach($params as $key => $value){
    if(preg_match("/^{{(.*)\.(.*)}}$/", $value, $matches)){
      if(isset($created[$matches[1]][$matches[2]])){
        $params[$key] = $created[$matches[1]][$matches[2]];
      }else{
        return false;
      }
    }
  }
  return true;
}
