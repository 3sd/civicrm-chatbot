<?php
abstract class CRM_Chat_Check{

  /**
  * Check to see if $text is a match
  * @param  string $text
  * @return boolean
  */
  final function matches($text){
    $this->text = $text;
    return $this->check();
  }

  /**
  * Return a textual representation of the match
  */
  final function getMatch(){
    return $this->match;
  }

  /**
  * Check to see if $this->match should be considered a match.
  *
  * Optionally alter the return value (e.g. if only part of the string should
  * be matched)
  */
  abstract function check();
  /**
  * Return a textual representation of the match
  */
  abstract function summarise();

  /**
   * get Form elements so they can be used to build the form
   */

  final function getFormElements(){
    return $this->formElements;
  }

}
