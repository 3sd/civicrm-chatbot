<?php
class CRM_Chat_Check_Contains extends CRM_Chat_Check {

  function __construct($params){
    $this->contains = $params['contains'];
  }

  function matches($text){
    if(strpos($text, $this->contains) !== false) {
      return true;
    }
    return false;
  }

  function summarise(){
    return "Answer contains '{$this->contains}'";
  }
}
