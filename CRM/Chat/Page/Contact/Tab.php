<?php
use CRM_Chat_ExtensionUtil as E;

class CRM_Chat_Page_Contact_Tab extends  CRM_Core_Page {

  public function run() {
    // Get contact Id
    $this->_contactId = CRM_Utils_Request::retrieve('cid', 'Positive', $this, TRUE);
    $this->assign('contactId', $this->_contactId);
    CRM_Utils_System::setTitle(E::ts('View conversations'));

    // check logged in url permission
    CRM_Contact_Page_View::checkUserPermission($this);

    $this->ajaxResponse['tabCount'] = CRM_Chat_Utils::getChatCount($this->_contactId);
    return parent::run();
  }
}
