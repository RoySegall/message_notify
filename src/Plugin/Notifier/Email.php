<?php

namespace Drupal\message_notify\Plugin\Notifier;

use Drupal\message\Entity\Message;
use Drupal\message_notify\MessageNotifierAbstract;
use Drupal\message_notify\MessageNotifyException;

/**
 * Redirects to a message deletion form.
 *
 * @Notify(
 *  id = "Email",
 *  label = @Translation("Email"),
 *  view_modes = {
 *    "email_subject" = @Translation("Notify - Email subject"),
 *    "email_body" = @Translation("Notify - Email body"),
 *  },
 * )
 */
class Email extends MessageNotifierAbstract {

  /**
   * {@inheritdoc}
   */
  function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    if (!$configuration['message'] instanceof Message) {
      $message = t('An object from the Message instance is missing.');
      watchdog('message_notify', $message);
      throw new MessageNotifyException($message);
    }

    $this->setMessage($configuration['message']);
  }

  /**
   * {@inheritdoc}
   */
  public function deliver() {
    $message = $this->message;

    if (!$message->getAuthor()) {
      if (!$this->destination) {
        $param['@type'] = $message->getType()->label();
        $error = t('The message from the type of @type could not be sent since she belong to anonymous user.', $param);
        watchdog('message_notify',$error);
        drupal_set_message($error, 'error');
        return '';
      }

      $mail = $this->destination;
    }
    else {
      $mail = $message->getAuthor()->getEmail();
    }

    if (!$this->settings['language override']) {
      $lang = $message->getAuthor()->getPreferredLangcode() ? $message->getAuthor()->getPreferredLangcode() : \Drupal::languageManager()->getCurrentLanguage()->getId();
    }
    else {
      $lang = $message->getTranslationLanguages();
    }

    // The subject in an email can't be with HTML, so strip it.
    $output['email_subject'] = strip_tags($this->viewModes['email_subject']);

    // Pass the view body view mode.
    $output['email_body'] = $this->viewModes['email_body'];

    // Pass the message entity along to hook_drupal_mail().
    $output['message_entity'] = $message;

    $result = drupal_mail('message_notify', $message->getType()->id(), $mail, $lang, $output);
    return $result['result'];
  }
}