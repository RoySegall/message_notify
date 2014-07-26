<?php

/**
 * @file
 * Contains \Drupal\message_notify\Annotation\Notify.
 */

namespace Drupal\message_notify\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a message notify annotation object.
 *
 * @Annotation
 */
class Notify extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The name of the person.
   *
   * @var string
   */
  public $name = 'John Doe';

  /**
   * The age of the person.
   *
   * @var string
   */
  public $age = '100';
}