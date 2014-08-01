<?php

namespace Drupal\message_notify\Plugin\Notifier;

use Drupal\message_notify\MessageNotifierAbstract;
use Drupal\message_notify\MessageNotifyException;


/**
 * Redirects to a message deletion form.
 *
 * @Notify(
 *  id = "SMS",
 *  label = @Translation("Email"),
 *  view_modes = {
 *    "sms_body" = @Translation("Notify - SMS Body"),
 *  },
 * )
 */
class Sms extends MessageNotifierAbstract {

  public function deliver() {
    if (empty($this->message->smsNumber)) {
      // Try to get the SMS number from the account.
      $account = user_load($this->message->uid);
      if (!empty($account->sms_user['number'])) {
        $this->message->smsNumber = $account->sms_user['number'];
      }
    }

    if (empty($this->message->smsNumber)){
      throw new MessageNotifyException('Message cannot be sent using SMS as the "smsNumber" property is missing from the Message entity or user entity.');
    }

    return sms_send($this->message->smsNumber, strip_tags($this->viewModes['sms_body']));
  }

  /**
   * The module can be exists only when the module SMS exists.
   */
  public function access() {
    return \Drupal::moduleHandler()->moduleExists('sms');
  }
}