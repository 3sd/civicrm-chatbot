<?php
use CRM_Chat_ExtensionUtil as E;

class CRM_Chat_BAO_ChatHear extends CRM_Chat_DAO_ChatHear {

  static function getActive(){
      $hears = new self;
      $hears->find();
      return $hears;
  }

}
