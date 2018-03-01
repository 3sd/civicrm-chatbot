<?php
use CRM_Chatbot_ExtensionUtil as E;
class CRM_Chatbot_Navigation {
  static function getItems(){
    return [
      [
        'label' => E::ts('Chat'),
        'parent' => '',
        'name' => 'chatbot',
        'url' => '',
        'permission' => 'access chatbot',
        'operator' => 'OR',
        'separator' => 0,
        'weight' => 60,
      ],
      [
        'label' => E::ts('Dashboard'),
        'parent' => 'chatbot',
        'name' => 'chatbot_dashboard',
        'url' => 'civicrm/chat',
        'permission' => 'access chatbot',
        'operator' => 'OR',
        'separator' => 0,
        'weight' => 60,
      ],
      [
        'label' => E::ts('Chat settings'),
        'parent' => 'Administer/System Settings',
        'name' => 'chatbot_settings',
        'url' => 'civicrm/admin/chat',
        'permission' => 'administer CiviCRM',
        'operator' => 'OR',
        'separator' => 0,
        'weight' => 60,
      ],
    ];
  }
}
