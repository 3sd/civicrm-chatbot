<?php
require_once(__DIR__.'/../../../../vendor/autoload.php');

/**
 * This is a generic test class for the extension (implemented with PHPUnit).
 */
class CRM_Chatbot_Base extends \PHPUnit_Framework_TestCase {

  /**
   * The setup() method is executed before the test is executed (optional).
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * The tearDown() method is executed after the test was executed (optional)
   * This can be used for cleanup.
   */
  public function tearDown() {
    parent::tearDown();
  }

}
