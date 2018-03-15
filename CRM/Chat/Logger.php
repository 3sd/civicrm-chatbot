<?php
// class CRM_Chat_Logger extends Psr\Log\AbstractLogger implements \Psr\Log\LoggerInterface{
class CRM_Chat_Logger {

  static function debug($message){

    if(!is_string($message)){
      $message = var_export($message, true);
    }
    file_put_contents(CRM_Core_Config::singleton()->configAndLogDir . '/chatbot.log', $message . "\n", FILE_APPEND);
    error_log($message);

  }

}
