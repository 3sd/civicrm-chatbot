<?php
// class CRM_Chat_Logger extends Psr\Log\AbstractLogger implements \Psr\Log\LoggerInterface{
class CRM_Chat_Logger {

  static function debug($message){

    if(!is_string($message)){
      $message = var_export($message, true);
    }
    foreach(explode("\n", $message) as $line){
      file_put_contents(CRM_Core_Config::singleton()->configAndLogDir . '/chatbot.log', $line . "\n", FILE_APPEND);
      error_log($line);
    }
  }
}
