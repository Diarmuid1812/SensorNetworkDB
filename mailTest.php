<?php

/**
 * @param int $sensorID
 * @param float $valTemp
 * @param float $valHum
 * @param float $valBatt
 * @param bool $isTemp
 * @param bool $TempHigh is temperature too high
 * @param bool $isHum
 * @param bool $HumHigh is humidity too high
 * @param bool $isBattery
 * @throws Exception mail not sent
 */
function sendAlarm(int $sensorID, float $valTemp, float $valHum, float $valBatt, bool $isTemp, bool $TempHigh,
                   bool $isHum=false, bool $HumHigh=false, bool $isBattery=false)
{
    try
    {
        require "config_db.php";

        /** @var $dbLink PDO */
        $qryPlacement = "SELECT miejsce FROM czujniki WHERE programowy_nr IS :sensorID";

        $stmt = $dbLink->prepare($qryPlacement);
        $stmt->bindParam(":sensorID", $sensorID, PDO::PARAM_INT);
        $place = $stmt->execute()['miejsce'];

        $qryEmail = "SELECT email FROM users";

        $address = array();
        foreach ($dbLink->query($qryEmail) as $row)
            $address[] = $row['email'];
        $recipients = implode(", ", $address);

        $stateTemp = $TempHigh ? "za wysoka" : "za niska";
        $stateHum = $HumHigh ? "za wsoka" : "za niska";

        $batt = $isBattery ? "za niski poziom naładowania baterii" : "";
        $temp = $isTemp ? "$stateTemp temperatura" : "";
        $hum = $isHum ? "$stateHum wilgotność" : "";

    }
    catch (PDOException $e)
    {
        echo $e->getMessage();
        throw $e;
    }


    if($isBattery&&$isHum&&$isTemp)
    {
        $head = $temp.", ".$hum." i ".$batt;
    }
    elseif ($isTemp&&$isHum)
        $head = $temp." i ".$hum;
    elseif ($isBattery&&$isHum)
        $head = $hum." i ".$batt;
    elseif ($isTemp&&$isBattery)
        $head = $temp." i ".$batt;
    else
        $head = $batt.$temp.$hum;


    $subject = "Alarm $head, czujnik: $sensorID";

    $alarm_text = "
    <h2>Wykryto przekroczenie dopuszczalnych wartości: </h2>
    <p>$head</p>
    <p>Dla czujnika: $sensorID</p>
    <p>W miejscu: $place</p>
    <p>Odczytane wartości: tempertura &ndash; $valTemp, wilgotność &ndash; $valHum,   
    bateria &ndash; $valBatt.</p>";


    try
    {
        mailTo($recipients, $subject, $alarm_text);
    }
    catch (Exception $e)
    {
        throw $e;
    }

    unset($dbLink);
    unset($stmt);
}

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