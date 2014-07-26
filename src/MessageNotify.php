<?php

namespace Drupal\message_notify;

class MessageNotify {

  /**
   * Get all the notifiers plugins or a specific one.
   *
   * @param null $type
   */
  public static function GetNotifiers($type = NULL) {
    $notifiers = \Drupal::service('plugin.manager.message.notify')->getDefinitions();
    return $type ? $notifiers[$type] : $notifiers;
  }
}