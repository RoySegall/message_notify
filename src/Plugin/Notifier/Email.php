<?php

namespace Drupal\message_notify\Plugin\Notifier;

use Drupal\message_notify\MessageNotifierAbstract;

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

  public function deliver(array $output = array()) {
    $message = $this->message;

    $options = $plugin['options'];

    $mail = $options['mail'] ? $options['mail'] : $this->message->getAuthor()->getEmail();

    $languages = \Drupal::languageManager()->getLanguages();
    if (!$options['language override']) {
      $lang = !empty($account->language) && $account->language != LANGUAGE_NONE ? $languages[$account->language]: language_default();
    }
    else {
      $lang = $languages[$message->language];
    }

    // The subject in an email can't be with HTML, so strip it.
    $output['message_notify_email_subject'] = strip_tags($output['message_notify_email_subject']);

    // Pass the message entity along to hook_drupal_mail().
    $output['message_entity'] = $message;

    $result =  drupal_mail('message_notify', $message->type, $mail, $lang, $output);
    return $result['result'];
  }
}