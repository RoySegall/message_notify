<?php

/**
 * @file
 * Contains \Drupal\message_notify\Plugin\MessageNotifyPluginManager.
 */

namespace Drupal\message_notify\Plugin;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Language\LanguageManager;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Manages Person plugins.
 */
class MessageNotifyPluginManager extends DefaultPluginManager {

  /**
   * {@inheritdoc}
   */
  public function __construct($subdir, \Traversable $namespaces, ModuleHandlerInterface $module_handler, $plugin_definition_annotation_name = 'Drupal\Component\Annotation\Plugin') {
    parent::__construct('Plugin/Notifier', $namespaces, $module_handler, 'Drupal\message_notify\Annotation\Notify');
  }

}