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

  public function setUp() {
    parent::setUp('message_notify_test');

    $this->MessageType = MessageType::create(array('type' => 'foo'))
      ->set('text', array('first partial', 'second partial'))
      ->save();

    return;

    // Enable the Full view mode, hide the first partial,
    // and display the last partial first.
    $settings = field_bundle_settings('message', 'foo');
    $settings['view_modes']['full']['custom_settings'] = TRUE;
    $settings['extra_fields']['display']['message__message_text__0']['foo'] = array('weight' => 0, 'visible' => FALSE);
    $settings['extra_fields']['display']['message__message_text__1']['foo'] = array('weight' => 0, 'visible' => TRUE);
    $settings['extra_fields']['display']['message__message_text_another__0']['foo'] = array('weight' => 0, 'visible' => FALSE);

    $settings['extra_fields']['display']['message__message_text__0']['bar'] = array('weight' => 0, 'visible' => FALSE);
    $settings['extra_fields']['display']['message__message_text__1']['bar'] = array('weight' => 0, 'visible' => FALSE);
    $settings['extra_fields']['display']['message__message_text_another__0']['bar'] = array('weight' => 0, 'visible' => TRUE);
    field_bundle_settings('message', 'foo', $settings);
  }

  /**
   * Helper function to attach rendred fields.
   *
   * @see MessageNotifyNotifier::testPostSendRenderedField()
   */
  public function attachRenderedFields() {
    foreach (array('rendered_foo', 'rendered_bar', 'rendered_baz') as $field_name) {
      $field = array(
        'field_name' => $field_name,
        'type' => 'text_long',
        'entity_types' => array('message'),
      );
      $field = field_create_field($field);
      $instance = array(
        'field_name' => $field_name,
        'bundle' => 'foo',
        'entity_type' => 'message',
        'label' => $field_name,
      );

      if ($field_name == 'rendered_baz') {
        $instance['settings'] = array(
          'text_processing' => 1,
        );
      }
      field_create_instance($instance);
    }
  }
}

