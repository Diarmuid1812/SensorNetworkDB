<?php
$to="241479@student.pwr.edu.pl";
$subject="Test wysylania maila z php";
$message="Możesz spróbować wysłać. Domyślnie jest ustawione jako do Ciebie, ale trzeba odpowiednio zmienić adres przy 
    'From:' na taki, który ogarnie Twój komputer... Jeszcze to przeanalizuję -- na pewno łatwiej jest wysłać z komputera
     z odpalonym serwerem mailowym xd.";
$from="From: szustakiewicz.m.f@gmail.com";

if(mail($to, $subject, $message,$from)){
    echo 'Your mail has been sent successfully.';
} else{
    echo 'Unable to send email. Please try again.';
}
