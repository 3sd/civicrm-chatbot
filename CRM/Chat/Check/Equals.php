<?php
class CRM_Chat_Check_Equals extends CRM_Chat_Check {

  function __construct($params){
    $this->equals = $params['equals'];
  }

  function check(){
    if(strtolower($this->text) == strtolower($this->equals)){
      return true;
    }
    return false;
  }

  function summarise(){
    return "answer equals '{$this->equals}'";
  }
}
