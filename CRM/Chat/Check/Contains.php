<?php
class CRM_Chat_Check_Contains extends CRM_Chat_Check {

  function __construct($params){
    $this->contains = $params['contains'];
  }

  function check(){
    if(strpos(strtolower($this->text), strtolower($this->contains)) !== false) {
      return true;
    }
    return false;
  }

  function summarise(){
    return "answer contains '{$this->contains}'";
  }
}
