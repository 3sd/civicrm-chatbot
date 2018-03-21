<?php
$tableTranslation = [];
function getTableName($entityName){
  global $tableTranslation;
  if(!isset($tableTranslation[$entityName])){
    $tableTranslation[$entityName] = CRM_Core_DAO_AllCoreTables::getTableForClass(CRM_Core_DAO_AllCoreTables::getFullName($entityName));
  }
  return $tableTranslation[$entityName];
}

function getEntityName($tableName){
  global $tableTranslation;
  if(!array_search($tableName, $tableTranslation)){
    $tableTranslation[CRM_Core_DAO_AllCoreTables::getBriefName(CRM_Core_DAO_AllCoreTables::getClassForTable($tableName))] = $tableName;
  }
  return array_search($tableName, $tableTranslation);
}

try {
  $batchId = civicrm_api3('Batch', 'getvalue', [
    'return' => 'id',
    'name' => 'chatbotDemoData'
  ]);
} catch (\Exception $e) {
  $batch = civicrm_api3('Batch', 'create', [
    'title' => 'chatbotDemoData',
    'status_id' => 'Open'
  ]);
  $batchId = $batch['id'];
}

//Clear conversation 'state' and chat users
$entity = new CRM_Chat_DAO_ChatCache;
$entity->whereAdd('id > 0');
$entity->delete(DB_DATAOBJECT_WHEREADD_ONLY);

$entity = new CRM_Chat_DAO_ChatUser;
$entity->whereAdd('id > 0');
$entity->delete(DB_DATAOBJECT_WHEREADD_ONLY);

$entityCount =  civicrm_api3('EntityBatch', 'getcount', [
  'batch_id' => $batchId
]
);

$entitiesToDelete = civicrm_api3('EntityBatch', 'get', [
  'batch_id' => $batchId,
  'options.limit' => $entityCount
]);

foreach($entitiesToDelete['values'] as $d){
  try {
    civicrm_api3(getEntityName($d['entity_table']), 'delete', ['id' => $d['entity_id']]);
  } catch (\Exception $e) {}
  try {
    civicrm_api3('EntityBatch', 'delete', ['id' => $d['id']]);
  } catch (\Exception $e) {}
}

$entities['ChatConversationType'] = [
  'pets' => [
    'name' => 'Cats or dogs?',
    'timeout' => '30'
  ],
  'email' => [
    'name' => 'Email address',
    'timeout' => '30'
  ],
];

$entities['ChatHear'] = [
  'petsurvey' => [
    'text' => 'pet survey',
    'chat_conversation_type_id' => '{{ChatConversationType.pets}}'
  ],
  'email' => [
    'text' => 'email',
    'chat_conversation_type_id' => '{{ChatConversationType.email}}'
  ],
];

$entities['ChatQuestion'] = [
  'dogorcat' => [
    'conversation_type_id' => '{{ChatConversationType.pets}}',
    'text' => 'Are you a dog person or a cat person?',
  ],
  'haveDog' => [
    'conversation_type_id' => '{{ChatConversationType.pets}}',
    'text' => 'Do you have a dog?',
  ],
  'haveCat' => [
    'conversation_type_id' => '{{ChatConversationType.pets}}',
    'text' => 'Do you have a cat?',
  ],
  'catsName' => [
    'conversation_type_id' => '{{ChatConversationType.pets}}',
    'text' => 'What is your cats name?',
  ],
  'dogNewsletter' => [
    'conversation_type_id' => '{{ChatConversationType.pets}}',
    'text' => 'Would you like to join our newsletter for Dog owners? (please answer yes or no)'
  ],
  'email' => [
    'conversation_type_id' => '{{ChatConversationType.email}}',
    'text' => 'What is your email address?',
  ],
  'petSurvey' => [
    'conversation_type_id' => '{{ChatConversationType.email}}',
    'text' => 'Would you like to take part in our pet survey?',
  ],
];

$entities['ChatAction'] = [
  'haveDog' => [
    'question_id' => '{{ChatQuestion.dogorcat}}',
    'type' => 'next',
    'check_object' => serialize(new CRM_Chat_Check_Contains(['contains' => 'dog'])),
    'action_data' => '{{ChatQuestion.haveDog}}'
  ],
  'dogNewsletter' => [
    'question_id' => '{{ChatQuestion.haveDog}}',
    'type' => 'next',
    'check_object' => serialize(new CRM_Chat_Check_Contains(['contains' => 'yes'])),
    'action_data' => '{{ChatQuestion.dogNewsletter}}'
  ],
  'dogNewsletterSignup' => [
    'question_id' => '{{ChatQuestion.dogNewsletter}}',
    'type' => 'group',
    'check_object' => serialize(new CRM_Chat_Check_Contains(['contains' => 'yes'])),
    'action_data' => '{{Group.dogNewsletter}}'
  ],
  'haveCat' => [
    'question_id' => '{{ChatQuestion.dogorcat}}',
    'type' => 'next',
    'check_object' => serialize(new CRM_Chat_Check_Contains(['contains' => 'cat'])),
    'action_data' => '{{ChatQuestion.haveCat}}'
  ],
  'catsName' => [
    'question_id' => '{{ChatQuestion.haveCat}}',
    'type' => 'next',
    'check_object' => serialize(new CRM_Chat_Check_Contains(['contains' => 'yes'])),
    'action_data' => '{{ChatQuestion.catsName}}'
  ],
  'catsNameAddField' => [
    'question_id' => '{{ChatQuestion.catsName}}',
    'type' => 'field',
    'check_object' => serialize(new CRM_Chat_Check_Anything()),
    'action_data' => 'placeholder'
  ],
  'email' => [
    'question_id' => '{{ChatQuestion.email}}',
    'type' => 'field',
    'check_object' => serialize(new CRM_Chat_Check_Anything()),
    'action_data' => 'email',
  ],
  'askAboutPetSurvey' => [
    'question_id' => '{{ChatQuestion.email}}',
    'type' => 'field',
    'check_object' => serialize(new CRM_Chat_Check_Anything()),
    'action_data' => 'first_name'
  ],
  'LastNameField' => [
    'question_id' => '{{ChatQuestion.email}}',
    'type' => 'field',
    'check_object' => serialize(new CRM_Chat_Check_Anything()),
    'action_data' => 'last_name'
  ]
];

$entities['Group'] = [
  'dogNewsletter' => [
    'title' => 'Dog newsletter',
    'type' => 'Mailing list',
  ]
];

$entities['CustomGroup'] = [
  'petData' => [
    'title' => 'Pets',
    'extends' => 'Contact',
    'extends_entity_column_value' => 'Individual',
    'collapse_display' => false,
    'style' => 'inline'
  ]
];

$entities['CustomField'] = [
  'catName' => [
    'label' => 'Cat name',
    'data_type' => 'String',
    'html_type' => 'Text',
    'custom_group_id' => '{{CustomGroup.petData}}',
  ]
];

while(createEntities($entities, $created, $batchId));

$entities['ChatConversationType'] = [
  'pets' => [
    'id' => $created['ChatConversationType']['pets'],
    'first_question_id' => $created['ChatQuestion']['dogNewsletter']
  ],
  'email' => [
    'id' => $created['ChatConversationType']['email'],
    'first_question_id' => $created['ChatQuestion']['email']
  ],
];

$entities['ChatAction'] = [
  'catsNameAddField' => [
    'id' => $created['ChatAction']['catsNameAddField'],
    'action_data' => 'custom_'.$created['CustomField']['catName']
  ]
];

while(createEntities($entities, $created, $batchId));

////////////////////////////////////////////////////////////////////////////////

function createEntities(&$entities, &$created, $batchId){
  foreach($entities as $type => $objects){
    foreach($objects as $tag => $params){
      if(substitute($params, $created)){
        echo "Processing $type.$tag...\r";
        try {
          $entityResult = civicrm_api3($type, 'create', $params);
        } catch (\Exception $e) {
          var_dump($params);
          exit;
        }

        if(isset($params['id'])){
          echo "Updated $type.$tag({$entityResult['id']})\n";
        }else{
          echo "Created $type.$tag({$entityResult['id']})\n";
          $batchResult = civicrm_api3('EntityBatch', 'create', [
            'entity_table' => getTableName($type),
            'entity_id' => $entityResult['id'],
            'batch_id' => $batchId
          ]);
        }
        $created[$type][$tag] = $entityResult['id'];
        unset($entities[$type][$tag]);
      }
    }
    if(count($entities[$type]) == 0){
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
        echo "Could not substitute {$params[$key]}\n";
        return false;
      }
    }
  }
  return true;
}
