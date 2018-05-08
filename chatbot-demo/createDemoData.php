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
  'movie' => [
    'name' => 'Favourite movie',
    'timeout' => '30'
  ],
];

$entities['ChatHear'] = [
  'pets' => [
    'text' => 'start',
    'chat_conversation_type_id' => '{{ChatConversationType.pets}}'
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
  'movie' => [
    'conversation_type_id' => '{{ChatConversationType.movie}}',
    'text' => 'What is your favourite movie?',
  ],
  'petSurvey' => [
    'conversation_type_id' => '{{ChatConversationType.movie}}',
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
  'dogNewsletterSignupConfirmYes' => [
    'question_id' => '{{ChatQuestion.dogNewsletter}}',
    'type' => 'say',
    'check_object' => serialize(new CRM_Chat_Check_Contains(['contains' => 'yes'])),
    'action_data' => "OK - we'll sign you up to the Dog newsletter!"
  ],
  'dogNewsletterSignupConfirmNo' => [
    'question_id' => '{{ChatQuestion.dogNewsletter}}',
    'type' => 'say',
    'check_object' => serialize(new CRM_Chat_Check_Contains(['contains' => 'no'])),
    'action_data' => "Understood - we won't add you to our Dog newsletter"
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
  'movie' => [
    'question_id' => '{{ChatQuestion.movie}}',
    'type' => 'say',
    'check_object' => serialize(new CRM_Chat_Check_Anything()),
    'action_data' => 'Thanks for letting me know!',
  ],
  'askAboutPetSurvey' => [
    'question_id' => '{{ChatQuestion.movie}}',
    'type' => 'next',
    'check_object' => serialize(new CRM_Chat_Check_Anything()),
    'action_data' => '{{ChatQuestion.petSurvey}}'
  ],
  'startPetSurvey' => [
    'question_id' => '{{ChatQuestion.petSurvey}}',
    'type' => 'conversation',
    'check_object' => serialize(new CRM_Chat_Check_Contains(['contains' => 'yes'])),
    'action_data' => '{{ChatConversationType.pets}}'
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
    'extends' => 'Individual',
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
    'first_question_id' => $created['ChatQuestion']['dogorcat']
  ],
  'movie' => [
    'id' => $created['ChatConversationType']['movie'],
    'first_question_id' => $created['ChatQuestion']['movie']
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
