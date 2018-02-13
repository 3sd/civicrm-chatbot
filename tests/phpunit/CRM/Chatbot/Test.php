<?php
use BotMan\BotMan\BotManFactory;
/**
 * This is a generic test class for the extension (implemented with PHPUnit).
 */
class CRM_Chatbot_Test extends CRM_Chatbot_Base {

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

  /**
   * Simple example test case.
   *
   * Note how the function name begins with the word "test".
   */
  public function testExample() {
    $b = BotManFactory::create([]);
    $this->assertEquals(get_class($b), 'BotMan\BotMan\BotMan');
  }
  
}
