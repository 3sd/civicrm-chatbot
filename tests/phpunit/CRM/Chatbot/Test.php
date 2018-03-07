<?php

use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * @group headless
 */
abstract class CRM_Chatbot_Test extends \PHPUnit_Framework_TestCase implements HeadlessInterface, HookInterface, TransactionalInterface {

  function setUpHeadless() {

    return Civi\Test::headless()
      ->installMe(__DIR__)
      ->apply();
  }

}
