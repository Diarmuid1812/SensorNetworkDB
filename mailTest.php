<?php
/**configure stmp server first*/
$to="241559@student.pwr.edu.pl";
$subject="Test wysylania maila z php";
$message="test wysłania";
$from="From: noreply @ company . com";

if(mail($to, $subject, $message,$from)){
    echo '<p>Your mail has been sent successfully.</p>';
} else{
    echo '<p>Unable to send email. Please try again.</p>';
}
