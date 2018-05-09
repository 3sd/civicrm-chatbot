<?php
return [
  [
    'name' => 'Incoming chat message',
    'entity' => 'OptionValue',
    'params' => [
      'version' => 3,
      'option_group_id' => 'activity_type',
      'label' => 'Incoming chat',
      'name' => 'Incoming chat',
      'description' => 'Chat message received from a contact',
      'filter' => '1',
      'is_reserved' => '1',
      'is_active' => '1'
    ]
  ],
  [
    'name' => 'Outgoing chat message',
    'entity' => 'OptionValue',
    'params' => [
      'version' => 3,
      'option_group_id' => 'activity_type',
      'label' => 'Outgoing chat',
      'name' => 'Outgoing chat',
      'description' => 'Chat message send to a contact',
      'filter' => '1',
      'is_reserved' => '1',
      'is_active' => '1'
    ]
  ],
  [
    'name' => 'Conversation',
    'entity' => 'OptionValue',
    'params' => [
      'version' => 3,
      'option_group_id' => 'activity_type',
      'label' => 'Conversation',
      'name' => 'Conversation',
      'description' => 'A conversation with a contact',
      'filter' => '1',
      'is_reserved' => '1',
      'is_active' => '1'
    ]
  ],
];
