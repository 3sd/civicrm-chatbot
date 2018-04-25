<?php
return [
  [
    'name' => 'match type',
    'entity' => 'OptionGroup',
    'params' => [
      'version' => 3,
      'name' => 'chat_check_type',
      'title' => 'Chat match type',
      'description' => 'Method for matching text in incoming chat messages',
      'is_reserved' => '1',
      'is_active' => '1'
    ]
  ],
  [
    'name' => 'match type anything',
    'entity' => 'OptionValue',
    'params' => [
      'version' => 3,
      'option_group_id' => 'chat_check_type',
      'label' => 'Match anything',
      'name' => 'Match anything',
      'value' => 'CRM_Chat_Check_Anything',
      'is_reserved' => '1',
      'is_active' => '1'
    ]
  ],
  [
    'name' => 'match type contains',
    'entity' => 'OptionValue',
    'params' => [
      'version' => 3,
      'option_group_id' => 'chat_check_type',
      'label' => 'Answer contains',
      'name' => 'Answer contains',
      'value' => 'CRM_Chat_Check_Contains',
      'is_reserved' => '1',
      'is_active' => '1'
    ]
  ],
  [
    'name' => 'match type equals',
    'entity' => 'OptionValue',
    'params' => [
      'version' => 3,
      'option_group_id' => 'chat_check_type',
      'label' => 'Answer is exactly',
      'name' => 'Answer is exactly',
      'value' => 'CRM_Chat_Check_Equals',
      'is_reserved' => '1',
      'is_active' => '1'
    ]
  ],
];
