<?php

namespace Drupal\message_notify;

use Drupal\message\Entity\Message;
use Drupal\message\Entity\MessageType;

class MessageNotify {

  /**
   * Get all the notifiers plugins or a specific one.
   *
   * @param $type
   *  The id of the plugin.
   *
   * @return null|array
   */
  public static function Notifiers($type = NULL) {
    $notifiers = \Drupal::service('plugin.manager.message.notify')->getDefinitions();

    if ($type) {
      return isset($notifiers[$type]) ? $notifiers[$type] : NULL;
    }

    return $notifiers;
  }

  /**
   * Load a given notifier.
   *
   * @param $type
   *  The notifier ID.
   * @param array $settings
   *  Default settings for the notifier handler.
   *  @code
   *    $message = Message::Load(1);
   *    array('message' => $message)
   *  @encode
   *
   * @return bool|MessageNotifierAbstract
   *  A notify instance.
   */
  public static function Notifier($type, array $settings = array()) {

    if (!self::Notifiers($type)) {
      return FALSE;
    }

    /** @var MessageNotifierAbstract $instance */
    $instance = \Drupal::service('plugin.manager.message.notify')->createInstance($type, $settings);

    return $instance->access() ? $instance : NULL;
  }

  /**
   * This is for debugging. Leave after stabilise the module.
   */
  public static function content() {

    // todo: remove after finishing with porting.
    if (!MessageType::load('dummy_message')) {
      $messageType = MessageType::Create(array(
        'type' => 'dummy_message',
        'label' => 'Dummy message',
        'description' => 'foo',
      ));
      $messageType->setText(array('Testing'));
      $messageType->save();
    }

    /** @var \Drupal\message\Entity\Message $message */
    $message = entity_create('message', array('type' => 'dummy_message'));
    $message->setAuthorId(1);

    self::Notifier('Email', array('message' => $message))
      ->setMessage($message)
      ->send();

    return '';
  }

  /**
   * Send the message.
   *
   * @param Message $message
   *   The message object.
   * @param String $notifier
   *   The notifier ID. Default to Email.
   * @param Array $options
   *   Additional options that will be passed to the notifier plugin.
   */
  static function sendMessage(Message $message, $notifier = 'Email', $options = array()) {
    $options = $options + array('message' => $message);
    MessageNotify::Notifier($notifier, $options)
      ->setMessage($message)
      ->send();
  }
}
