<?php

namespace Drupal\message_notify;

use Drupal\message\Entity\Message;

interface MessageNotifierInterface {

  /**
   * Constructor for the notifier.
   *
   * @param $plugin
   *   The notifier plugin object. Note the "options" values might have
   *   been overridden in message_notify_send_message().
   * @param Message $message
   *   The Message entity.
   */
  public function __construct($plugin, Message $message);

  /**
   * Entry point to send and process a message.
   *
   * @return
   *   TRUE or FALSE based on delivery status.
   */
  public function send();

  /**
   * Deliver a message via the required transport method.
   *
   * @param $output
   *   Array keyed by the view mode, and the rendered entity in the
   *   specified view mode.
   *
   * @return
   *   TRUE or FALSE based on delivery status.
   */
  public function deliver(array $output = array());

  /**
   * Post send operations.
   */
  public function postSend($result, array $output = array());

  /**
   * Determine if user can access notifier.
   */
  public function access();
}
