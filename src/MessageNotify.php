<?php

namespace Drupal\message_notify;

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
  public static function GetNotifiers($type = NULL) {
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
   *
   * @return bool|MessageNotifierAbstract
   *  A notify instance.
   */
  public static function GetNotifier($type) {

    if (!self::GetNotifiers($type)) {
      return FALSE;
    }

    /** @var MessageNotifierAbstract $instance */
    $instance = \Drupal::service('plugin.manager.message.notify')->createInstance($type);

    return $instance->access() ? $instance : NULL;
  }

  /**
   * This is for debugging. Leave after stabilise the module.
   */
  public static function content() {

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

    self::GetNotifier('Email')
      ->setMessage($message)
      ->send();

    return '';
  }
}
