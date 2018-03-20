<?php
use CRM_Chat_ExtensionUtil as E;

class CRM_Chat_BAO_ChatAction extends CRM_Chat_DAO_ChatAction {

  static function findByTypeAndQuestion($type, $questionId) {

    $action = new self;
    $action->type = $type;
    $action->question_id = $questionId;
    $action->find();

    return $action;

  }
}
