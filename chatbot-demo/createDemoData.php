<?php
//Delete all existing entities

$entities = [
  'CRM_Chat_DAO_ChatConversationType',
  'CRM_Chat_DAO_ChatHear',
  'CRM_Chat_DAO_ChatCache'
];
foreach($entities as $entity){
  echo "Deleting all {$entity} entities\n";
  $entity = new $entity;
  $entity->whereAdd('id > 0');
  $entity->delete(DB_DATAOBJECT_WHEREADD_ONLY);
}

echo "Creating demo data...\n";

$conversation = civicrm_api3('ChatConversationType', 'create', [
  'name' => 'Cats or dogs?',
  'timeout' => '30',
]);

$hears = civicrm_api3('ChatHear', 'create', [
  'text' => 'demo',
  'chat_conversation_type_id' => $conversation['id'],
]);
