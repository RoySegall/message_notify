<?php

namespace Drupal\message_notify\Tests;

use Drupal\message_notify\MessageNotify;
use Drupal\message_notify\MessageNotifyException;

/**
 * Test populating the rendered output to fields.
 *
 * @group Message notify
 */
class MessageNotifyPostSendRenderedFieldTest extends MessageNotifyBaseTest {

  /**
   * Test populating the rendered output to fields.
   */
  public function testPostSendRenderedField() {
    $this->attachRenderedFields();

    // Test plain fields.
    $options = array(
      'rendered fields' => array(
        'foo' => 'rendered_foo',
        'bar' => 'rendered_bar',
      ),
    );
    $message = message_create('foo');
    MessageNotify::sendMessage($message, $options, 'test');
    $wrapper = entity_metadata_wrapper('message', $message);
    $this->assertTrue($wrapper->rendered_foo->value() && $wrapper->rendered_bar->value(), 'Message is rendered to fields.');

    // Test field with text-processing.
    $options = array(
      'rendered fields' => array(
        'foo' => 'rendered_baz',
        'bar' => 'rendered_bar',
      ),
    );
    $message = message_create('foo');
    MessageNotify::sendMessage($message, $options, 'test');
    $wrapper = entity_metadata_wrapper('message', $message);
    $this->assertTrue($wrapper->rendered_baz->value->value() && $wrapper->rendered_bar->value(), 'Message is rendered to fields with text-processing.');

    // Test missing view mode key in the rendered fields.
    $options = array(
      'rendered fields' => array(
        'foo' => 'rendered_foo',
        // No "bar" field.
      ),
    );
    $message = message_create('foo');
    try {
      MessageNotify::sendMessage($message, $options, 'test');
      $this->fail('Can save rendered message with missing view mode.');
    }
    catch (MessageNotifyException $e) {
      $this->pass('Cannot save rendered message with missing view mode.');
    }

    // Test invalid field name.
    $options = array(
      'rendered fields' => array(
        'foo' => 'wrong_field',
        'bar' => 'rendered_bar',
      ),
    );
    $message = message_create('foo');
    try {
      MessageNotify::sendMessage($message, $options, 'test');
      $this->fail('Can save rendered message to non-existing field.');
    }
    catch (MessageNotifyException $e) {
      $this->pass('Cannot save rendered message to non-existing field.');
    }
  }
}