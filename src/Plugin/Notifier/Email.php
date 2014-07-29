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

    if (!$message->getAuthor()) {
      if (!$this->destination) {
        $param['@type'] = $message->getType()->label();
        $error = t('The message from the type of @type could not be sent since she belong to anonymous user.', $param);
        watchdog('message_notify',$error);
        drupal_set_message($error, 'error');
        return;
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
    $output['message_notify_email_subject'] = strip_tags($output['message_notify_email_subject']);

    // Pass the message entity along to hook_drupal_mail().
    $output['message_entity'] = $message;

    $result = drupal_mail('message_notify', $message->getType()->id(), $mail, $lang, $output);
    return $result['result'];
  }
}