<?php

class CRM_Chat_Page_AJAX {

  public static function getContactConversations() {
    $params = $_GET;
    $requiredParams = array(
      'cid' => 'Integer',
    );
    $optionalParams = array(
      'source_contact_id' => 'String',
      'status_id' => 'Integer',
    );
    $params = CRM_Core_Page_AJAX::defaultSortAndPagerParams();
    $params += CRM_Core_Page_AJAX::validateParams($requiredParams, $optionalParams);

    // get conversation list
    $conversations = self::getConversationList($params);
    CRM_Utils_JSON::output($conversations);
  }

  static function getConversationList($params) {
    $params['sequential'] = 1;
    $params['contact_id'] = $params['cid'];
    $params['activity_type_id'] = 'Conversation';

    $params['rowCount'] = $params['rp'];
    if (!empty($params['sortBy'])) {
      $params['sort'] = $params['sortBy'];
    }

    $DT['data'] = [];

    $conversations = civicrm_api3('Activity', 'get', $params);
    $activityStatuses = array_column(civicrm_api3('OptionValue', 'get', ['option_group_id' => 'activity_status'])['values'], 'label', 'value');
    // print_r($activityStatuses);

    foreach ($conversations['values'] as $conversation) {

      $sourceContact = civicrm_api3('Contact', 'getsingle', array(
        'return' => array("display_name"),
        'id' => $conversation['source_contact_id'],
      ));
      $url = CRM_Utils_System::url('civicrm/contact/view', 'reset=1&cid='.$conversation['source_contact_id']);
      $conversation['source_contact'] = "<a href='{$url}'>{$sourceContact['display_name']}</a>";

      // Format Date
      $conversation['date'] = CRM_Utils_Date::customFormat($conversation['activity_date_time']);
      // $conversation['status'] = $activityStatuses[]
      // Format current question for display (show a shortened (to 30 chars) question text label)
      $links = self::actionLinks();
      // Get mask
      $mask = CRM_Core_Action::VIEW;
      // switch ($conversation['status_id']) {
      //   case $scheduledId:
      //     // We show delete if in scheduled state
      //     $mask += CRM_Core_Action::DELETE;
      //     break;
      //   case $inProgressId:
      //     // We show cancel if in "In Progress" state
      //     $mask += CRM_Core_Action::UPDATE;
      //     break;
      // }
      $conversation['links'] = CRM_Core_Action::formLink($links,
        $mask,
        array(
          'id' => $conversation['id'],
          'cid' => $params['cid'],
        ),
        ts('more')
      );
      $DT['data'][] = $conversation;
    }
    $DT['recordsTotal'] = $conversations['count'];
    $DT['recordsFiltered'] = $DT['recordsTotal'];
    // var_dump($DT);exit;
    return $DT;
  }

  static function actionLinks() {
    $links = array(
      CRM_Core_Action::VIEW => array(
        'name' => ts('View'),
        'url' => 'civicrm/chat/conversation/view',
        'qs' => 'id=%%id%%',
        'title' => ts('View Conversation'),
        'class' => 'crm-popup',
      ),
      CRM_Core_Action::DELETE => array(
        'name' => ts('Delete'),
        'url' => 'civicrm/activity/add',
        'qs' => 'action=delete&id=%%id%%&cid=%%',
        'title' => ts('Delete Conversation'),
        'class' => 'crm-popup',
      ),
    );
    return $links;
  }
}
