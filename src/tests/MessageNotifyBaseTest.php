<?php

namespace Drupal\message_notify\Tests;

use Drupal\message\Entity\MessageType;
use Drupal\simpletest\WebTestBase;

/**
 * Test the Message notifier plugins handling.
 */
abstract class MessageNotifyBaseTest extends WebTestBase {

  /**
   * @var MessageType
   *
   * The message type.
   */
  public $MessageType;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp('message_notify_example');
  }
}

