<?php
/**
 * @param string $to
 * @param string $subject
 * @param string $message
 * @throws Exception failed to send mail
 */
function mailTo(string $to, string $subject, string $message)
{
    /**configure stmp server first*/
$from = "From: noreply @ company . com";

    if (mail($to, $subject, $message, $from))
    {
        echo '<p>Your mail has been sent successfully.</p>';

    } else
    {
        echo '<p>Unable to send email. Please try again.</p>';
        throw new Exception('Unable to send email. Please try again.');
    }
}