<?php
use CRM_Chat_ExtensionUtil as E;

class CRM_Chat_Page_ConversationType extends CRM_Core_Page {

  public function run() {

    // TODO Implement paging so we can display more that 25 conversation types :)

  $this->assign('conversationTypes', civicrm_api3('ChatConversationType', 'get')['values']);

  parent::run();

  }

}
