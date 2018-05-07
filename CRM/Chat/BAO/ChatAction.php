<?php
use CRM_Chat_ExtensionUtil as E;

class CRM_Chat_BAO_ChatAction extends CRM_Chat_DAO_ChatAction {

  static function findByTypeAndQuestion($type, $questionId) {

    $actions = new self;
    $actions->type = $type;
    $actions->question_id = $questionId;
    $actions->find();

    return $actions;

  }
}
