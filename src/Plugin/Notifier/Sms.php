<?php

namespace Drupal\message_notify\Plugin\Notifier;

use Drupal\message_notify\MessageNotifierAbstract;
use Drupal\message_notify\MessageNotifyException;


/**
 * Redirects to a message deletion form.
 *
 * todo: Add view modes.
 *
 * @Notify(
 *  id = "SMS",
 *  label = @Translation("Email"),
 * )
 */
class Sms extends MessageNotifierAbstract {

  public function deliver(array $output = array()) {
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

    return sms_send($this->message->smsNumber, strip_tags($output['message_notify_sms_body']));
  }
}