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
   * @return
   *   TRUE or FALSE based on delivery status.
   */
  public function deliver();

  /**
   * Post send operations; Save the rendered messages if needed. Invoke watchdog
   * error on failure.
   *
   * @param $result
   *   Results from the delivery method.
   * @param $output
   *   Array keyed by the view mode, and the rendered entity in the
   *   specified view mode.
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
   *   The class object.
   *
   * @return MessageNotifierInterface
   */
  public function setMessage(Message $message);

  /**
   * Set the settings for the plugin instance.
   *
   * @param array $settings
   *   Array of settings.
   *
   * @return $this.
   */
  public function setSettings(array $settings);

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

  /**
   * Retrieve the view modes rendered value.
   *
   * @return array.
   */
  public function getViewModes();
}
