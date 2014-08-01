<?php

/**
 * @file
 * Contains Drupal\ckeditor\Annotation\CKEditorPlugin.
 */

namespace Drupal\message_notify\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Define a message notify annotation object.
 *
 * @Annotation
 */
class Notify extends Plugin {

  /**
   * @var String
   *
   * The identifier of the plugin.
   */
  public $id;

  /**
   * @var String
   *
   * The label fo the plugin.
   */
  public $label;

  /**
   * @var Array.
   *
   * Holds the view mode of the plugin for the message type.
   */
  public $view_modes;

}
