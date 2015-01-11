<?php

namespace Drupal\message_notify\Tests;

use Drupal\message\Entity\Message;
use Drupal\message_notify\MessageNotify;

/**
 * Test send method.
 *
 * @group Message notify
 */
class MessageNotifyDeliverTest extends MessageNotifyBaseTest {

  /**
   * Test send method.
   *
   * Check the correct info is sent to delivery.
   */
  public function testDeliver() {
    $message = Message::create(array('type' => 'foo'));
    MessageNotify::sendMessage($message, 'Test');

    // The test notifier added the output to the message.
    $output = $message->output;
    $this->assertEqual($output['foo'], $wrapper->{MESSAGE_FIELD_MESSAGE_TEXT}->get(1)->value->value(), 'Correct values rendered in first view mode.');
    $this->assertEqual($output['bar'], $wrapper->message_text_another->value(), 'Correct values rendered in second view mode.');
  }
}