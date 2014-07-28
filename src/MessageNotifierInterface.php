<?php

namespace Drupal\message_notify;

use Drupal\message\Entity\Message;

interface MessageNotifierInterface {

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

  /**
   * Add Attachments.
   */
  public function setAttachments();

  /**
   * Get Attachments.
   */
  public function getAttachments();

  /**
   * Set the message property of the class.
   *
   * @param Message $message
   *  The class object.
   *
   * @return $this
   *
   * @todo: Create a dependency injection of the message object.
   */
  public function setMessage(Message $message);

  /**
   * Set the settings for the plugin instance.
   *
   * @return $this.
   */
  public function setSettings();

  /**
   * Retrieve the settings of the current instance.
   *
   * @return Array
   */
  public function getSettings();

  /**
   * Set the language code of the message text.
   */
  public function setLanguage($language);

  /**
   * Retrieve the language code of the message.
   */
  public function getLanguage();

}
