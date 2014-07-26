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
 * todo: Add default settings.
 *
 * @Annotation
 */
class Notify extends Plugin {

  public $id;
  public $label;

}
