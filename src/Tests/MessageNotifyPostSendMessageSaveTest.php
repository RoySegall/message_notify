<?php

namespace Drupal\message_notify\Tests;

use Drupal\message_notify\MessageNotify;

/**
 * Test Message save on delivery.
 *
 * @group Message notify
 */
class MessageNotifyPostSendMessageSaveTest extends MessageNotifyBaseTest {

  /**
   * Test Message save on delivery.
   */
  public function testPostSendMessageSave() {
    $message = message_create('foo');
    $message->fail = FALSE;
    MessageNotify::sendMessage($message, array(), 'test');
    $this->assertTrue($message->mid, 'Message not saved after successful delivery.');

    $message = message_create('foo');
    $message->fail = TRUE;
    MessageNotify::sendMessage($message, array(), 'test');
    $this->assertTrue($message->mid, 'Message not saved after unsuccessful delivery.');

    // Disable saving Message on delivery.
    $options = array(
      'save on fail' => FALSE,
      'save on success' => FALSE,
    );

    $message = message_create('foo');
    $message->fail = FALSE;
    MessageNotify::sendMessage($message, $options, 'test');
    $this->assertTrue($message->is_new, 'Message not saved after successful delivery.');

    $message = message_create('foo');
    $message->fail = TRUE;
    MessageNotify::sendMessage($message, $options, 'test');
    $this->assertTrue($message->is_new, 'Message not saved after unsuccessful delivery.');
  }
}