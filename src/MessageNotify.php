<?php

namespace Drupal\message_notify;

class MessageNotify {

  /**
   * Get all the notifiers plugins or a specific one.
   *
   * @param null $type
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

    $instance = \Drupal::service('plugin.manager.message.notify')->createInstance($type);

    return $instance->access() ? $instance : NULL;
  }

  public static function content() {
    $foo = self::GetNotifier('SMS');
    var_dump($foo);
    return '';
  }
}