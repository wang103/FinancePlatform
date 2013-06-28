<?php

/**
 * Send a notification email to someone.
 *
 * @param $from
 *   The sender of the email.
 * @param $to
 *   The receiver(s) of the email.
 * @param $subject
 *   The subject of the email. Cannot contain any newline characters.
 * @param $message
 *   The message to be sent. Each line is at most 70 characters.
 *
 * @return
 *   0 if success. Otherwise 1.
 */
function notifyWithEmail($from, $to, $subject, $message) {
    $headers = "From:" . $from;
    mail($to, $subject, $message, $headers);

    return 0;
}

?>
