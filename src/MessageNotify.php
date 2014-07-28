<?php

namespace Drupal\message_notify;

use Drupal\contact\Entity\Message;

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

    if (!\Drupal::moduleHandler()->moduleExists('message_example')) {
      drupal_set_message(t('The message example module need to be turned on for this page.'), 'error');

      return;
    }

    /** @var \Drupal\message\Entity\Message $message */
    $message = Message::create(array('type' => 'example_create_node'));

    self::GetNotifier('Email')
      ->setMessage($message)
      ->send();

    return '';
  }
}
