<?php

namespace Drupal\message_notify_example\Plugin\Notifier;

use Drupal\message_notify\MessageNotifierAbstract;

/**
 * Redirects to a message deletion form.
 *
 * @Notify(
 *  id = "Test",
 *  label = @Translation("Test"),
 *  description = @Translation("Notifier used for testing."),
 *  view_modes = {
 *    "foo" = @Translation("Foo"),
 *    "bar" = @Translation("Bar"),
 *  },
 * )
 */
class Test extends MessageNotifierAbstract {

  /**
   * {@inheritdoc}
   */
  public function deliver(array $output = array()) {
    $this->message->output = $output;
    // Return TRUE or FALSE as it was set on the Message.
    return empty($this->fail);
  }

}