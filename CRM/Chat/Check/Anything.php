<?php
class CRM_Chat_Check_Anything extends CRM_Chat_Check {

  function __construct(){
  }

  function check(){
    return true;
  }

  function summarise(){
    return "All answers";
  }
}
