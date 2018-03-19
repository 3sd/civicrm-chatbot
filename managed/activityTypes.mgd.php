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
      'is_reserved' => '1',
      'is_active' => '1'
    ]
  ],
];
