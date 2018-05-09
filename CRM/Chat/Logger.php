<?php
// class CRM_Chat_Logger extends Psr\Log\AbstractLogger implements \Psr\Log\LoggerInterface{
class CRM_Chat_Logger {

  static function debug($message){

    if(!is_string($message)){
      $message = var_export($message, true);
    }

    $backtrace = debug_backtrace();
    if (!empty($backtrace[0]) && is_array($backtrace[0])) {
      $fileinfo = $backtrace[0]['file'] . ":" . $backtrace[0]['line'];
    }
    // foreach(array_merge([$fileinfo], explode("\n", $message)) as $line){
    foreach(explode("\n", $message) as $line){
      file_put_contents(CRM_Core_Config::singleton()->configAndLogDir . '/chatbot.log', $line . "\n", FILE_APPEND);
      error_log($line);
    }
  }
}
