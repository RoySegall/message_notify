<?php

namespace Drupal\message_notify;

use Drupal\Core\Plugin\PluginBase;
use \Drupal\message\Entity\Message;

/**
 * An abstract implementation of MessageNotifierInterface.
 */
abstract class MessageNotifierAbstract extends PluginBase implements MessageNotifierInterface {

  /**
   * @var Message
   *
   * The message entity.
   */
  protected $message;

  /**
   * @var Array
   *
   * A list of attachments.
   */
  protected $attachment;

  /**
   * @var String
   *
   * Allow to override the destination of the message. i.e: when dealing with
   * email you can set the the email address or when dealing with SMS you can
   * set the phone number.
   */
  protected $destination;

  /**
   * @var Array.
   *
   * Holds settings for the current instance.
   */
  protected $settings;

  /**
   * @var String
   *
   * Holds the language of the message text meant to be sent.
   */
  protected $language;

  /**
   * @var array
   *
   * Holds the view modes rendered value.
   */
  protected $viewModes = array();

  /**
   * {@inheritdoc}
   */
  public function setMessage(Message $message) {
    $this->message = $message;

    return $this;
  }

  /**
   * Set a visibility of the plugin.
   *
   * @return bool
   */
  public function access() {
    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);

    $this->settings = array_merge($configuration, $this->settings);
  }

  /**
   * Set the attachment of the message.
   *
   * @return $this.
   */
  public function setAttachments() {
    return $this;
  }

  /**
   * Get the message attachment.
   */
  public function getAttachments() {
    return $this->attachment;
  }

  /**
   * {@inheritdoc}
   */
  public function setSettings(array $settings) {
    $this->settings = $settings;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSettings() {
    return $this->settings;
  }

  /**
   * {@inheritdoc}
   */
  public function setLanguage($language) {
    $this->language = $language;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getLanguage() {
    return $this->language;
  }

  /**
   * {@inheritdoc}
   */
  public function getViewModes() {
    return $this->viewModes;
  }

  /**
   * Retrieve the message object.
   *
   * @return Message.
   */
  public function getMessage() {
    return $this->message;
  }

  /**
   * {@inheritdoc}
   */
  public function send() {
    $output = array();

    foreach (array_keys($this->pluginDefinition['view_modes']) as $view_mode) {
      $view = entity_view($this->message, $view_mode);
      $this->viewModes[$view_mode] = render($view);
    }

    $result = $this->deliver();

    $this->postSend($result, $output);
    return $result;
  }

  /**
   * {@inheritdoc}
   */
  abstract public function deliver();

  /**
   * {@inheritdoc}
   */
  public function postSend($result, array $output = array()) {
    $plugin = $this->pluginDefinition;
    $message = $this->message;

    $save = FALSE;
    if (!$result) {
      \Drupal::logger('message_notify')->error(t('Could not send message using @title to user ID @uid.'), array('@label' => $plugin['title'], '@uid' => $message->uid));
      if ($this->settings['save on fail']) {
        $save = TRUE;
      }
    }
    elseif ($result && $this->settings['save on success']) {
      $save = TRUE;
    }

    if ($save) {
      $message->save();
    }
  }
}
