<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'chatbot.civix.php';
use CRM_Chat_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function chatbot_civicrm_config(&$config) {
  if (isset(Civi::$statics[__FUNCTION__])) {
    return;
  }
  Civi::$statics[__FUNCTION__] = 1;
  _chatbot_civix_civicrm_config($config);
  Civi::dispatcher()->addListener('hook_civicrm_post', 'chatbot_post_sms',1000);
}

function chatbot_post_sms($event){
  if($event->entity=='Activity' && $event->object->activity_type_id == CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'activity_type_id', 'Inbound SMS')) {
    $activity = civicrm_api3('Activity', 'getsingle', ['id' => $event->id]);
    $client = new GuzzleHttp\Client();
    try {

      $response = $client->request('POST', CRM_Utils_System::url('civicrm/chat/webhook/civisms', null, true), [
        'body' => json_encode([
          'authentication_token' => civicrm_api3('setting', 'getvalue', ['name' => 'chatbot_civisms_authentication_token']),
          'text' => $activity['details'],
          'contact_id' => $activity['source_contact_id']
        ])
      ]);
      echo (string) $response->getBody();

    } catch (\Exception $e) {

        echo $e->getResponse()->getBody()->getContents();
    }

  }
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function chatbot_civicrm_xmlMenu(&$files) {
  _chatbot_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function chatbot_civicrm_install() {
  _chatbot_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function chatbot_civicrm_postInstall() {
  _chatbot_civix_civicrm_postInstall();
  // Necessary since we are communicating with ourselves via public post requests
  // (since that is what Botman wants us to do)
  civicrm_api3('setting', 'create', ['chatbot_civisms_authentication_token' => CRM_Chat_Utils::generateToken()]);
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function chatbot_civicrm_uninstall() {
  _chatbot_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function chatbot_civicrm_enable() {
  _chatbot_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function chatbot_civicrm_disable() {
  _chatbot_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function chatbot_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _chatbot_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function chatbot_civicrm_managed(&$entities) {
  _chatbot_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function chatbot_civicrm_caseTypes(&$caseTypes) {
  _chatbot_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function chatbot_civicrm_angularModules(&$angularModules) {
  _chatbot_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function chatbot_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _chatbot_civix_civicrm_alterSettingsFolders($metaDataFolders);
}


/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_entityTypes
 */
function chatbot_civicrm_entityTypes(&$entityTypes) {
  _chatbot_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 **/
function chatbot_civicrm_navigationMenu(&$menu) {
  foreach(CRM_Chat_Navigation::getItems() as $item){
    _chatbot_civix_insert_navigation_menu($menu, $item['parent'], $item);
  }
  _chatbot_civix_navigationMenu($menu);
}

function chatbot_civicrm_permission(&$permissions){

  $prefix = E::ts('Chatbot') . ': ';

  $permissions['access chatbot'] = [
    $prefix . E::ts('access chatbot'),
    E::ts('Provides access to chatbot')
  ];
}

function chatbot_civicrm_summaryActions(&$actions, $contactId){

  // If the contact has a mobile phone, start a conversation with them
  $count = civicrm_api3('ChatUser', 'getcount', ['contact_id' => $contactId]);
  if($count){
      $actions['chatbot'] = [
      'title' => 'Chat - start a conversation',
      'weight' => 999,
      'ref' => 'chatbot',
      'key' => 'chatbot',
      'href' => CRM_Utils_System::url('civicrm/chat/start', "cid=$contactId"),
    ];
  }
}

function chatbot_civicrm_searchTasks( $objectName, &$tasks ){
  if($objectName == 'contact'){
    $tasks[] = [
      'title' => 'Chat - start a conversation',
      'class' => 'CRM_Chat_Form_StartMultiple'
    ];
  }
}

function chatbot_civicrm_tabs ( &$tabs, $contactId ) {
  $tabs[] = array(
    'title'  => 'Chat',
    'id'     => 'chat',
    'class' => 'livePage',
    'url'    => CRM_Utils_System::url('civicrm/contact/view/chat', "reset=1&cid={$contactId}"),
    'weight' => 50,
    'count'  => CRM_Chat_Utils::getChatCount($contactId)
  );
}
