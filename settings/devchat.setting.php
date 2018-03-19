<?php
return array(
  'chatbot_devchat_endpoint' => array(
    'group_name' => 'Chatbot',
    'group' => 'chatbot',
    'name' => 'chatbot_devchat_endpoint',
    'type' => 'String',
    'quick_form_type' => 'Element',
    'html_type' => 'Text',
    'title' => 'Endpoint of the devchat server',
    'description' => 'Endpoint of the devchat server',
    'help_text' => 'Endpoint of the devchat server (see https://github.com/3sd/civicrm-chatbot-devchat for more info)',
    'default' => 'http://devchat:3000/postMessage',
    'add' => '4.7',
    'is_domain' => 1,
    'is_contact' => 0,
  )
);
