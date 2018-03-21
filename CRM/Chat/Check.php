<?php
abstract class CRM_Chat_Check{

  /**
  * Check to see if $text is a match
  * @param  string $text
  * @return boolean
  */
  abstract function matches($text);

  /**
   * Return a textual representation of the match
   */

  abstract function summarise();
}
