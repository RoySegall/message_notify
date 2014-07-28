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
  }

  /**
   * {@inheritdoc}
   */
  public function setSettings() {
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
   * Retrieve the message object.
   *
   * @return Message.
   */
  public function getMessage() {
    return $this->message;
  }

  public function send() {
    $message = $this->message;
    $output = array();
    foreach ($this->pluginDefinition['view_modes'] as $view_mode => $value) {
//      $content = $message->buildContent($view_mode);
      $output[$view_mode] = 'a';
    }
    $result = $this->deliver($output);
    $this->postSend($result, $output);
    return $result;
  }

  public function deliver(array $output = array()) {}

  /**
   * Act upon send result.
   *
   * - Save the rendered messages if needed.
   * - Invoke watchdog error on failure.
   */
  public function postSend($result, array $output = array()) {
    $plugin = $this->plugin;
    $message = $this->message;

    $options = $plugin['options'];

    $save = FALSE;
    if (!$result) {
      watchdog('message_notify', t('Could not send message using @title to user ID @uid.'), array('@label' => $plugin['title'], '@uid' => $message->uid), WATCHDOG_ERROR);
      if ($options['save on fail']) {
        $save = TRUE;
      }
    }
    elseif ($result && $options['save on success']) {
      $save = TRUE;
    }

    if ($options['rendered fields']) {
      // Save the rendered output into matching fields.
      $wrapper = entity_metadata_wrapper('message', $message);
      foreach ($this->plugin['view_modes'] as $view_mode => $mode) {
        if (empty($options['rendered fields'][$view_mode])) {
          throw new MessageNotifyException(format_string('The rendered view mode @mode cannot be saved to field, as there is not a matching one.', array('@mode' => $mode['label'])));
        }
        $field_name = $options['rendered fields'][$view_mode];

        if (!$field = field_info_field($field_name)) {
          throw new MessageNotifyException(format_string('Field @field does not exist.', array('@field' => $field_name)));
        }

        // Get the format from the field. We assume the first delta is the
        // same as the rest.
        if (empty($wrapper->{$field_name}->format)) {
          $wrapper->{$field_name}->set($output[$view_mode]);
        }
        else {
          $format = $wrapper->type->{MESSAGE_FIELD_MESSAGE_TEXT}->get(0)->format->value();
          $wrapper->{$field_name}->set(array('value' => $output[$view_mode], 'format' => $format));
        }
      }
    }

    if ($save) {
      $message->save();
    }
  }
}
