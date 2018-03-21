<?php
class CRM_Chat_Check_Equals extends CRM_Chat_Match {

  function __construct($params){
    $this->equals = $params['equals'];
  }

  function matches($text){
    if($text == $this->equals){
      return true;
    }
    return false;
  }

  function summarise(){
    return "Answer equals '{$this->equals}'";
  }
}
