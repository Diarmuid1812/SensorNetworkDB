<?php
require_once "mailTest.php";
define('BATTERY_MIN', 5.00);
define('TEMP_MIN', 5.00);
define('TEMP_MAX', 5.00);
define('HUM_MIN', 5.00);
define('HUM_MAX', 5.00);

//create log
$f_hand = fopen("com_log.txt","w");
$tim = time();
$inf = "Log from last communication attempt.\n";
fwrite($f_hand,$inf);
$inf = date("d-m-Y H:i:s", $tim)."\n";
fwrite($f_hand,$inf);

if ($_SERVER["REQUEST_METHOD"] == "POST")
{

    ob_start();
    var_dump($_POST);
    $inf = ob_get_clean();
    fwrite($f_hand,$inf);

    $comID    = trim(filter_var($_POST['id'], FILTER_VALIDATE_INT));
    $comTemp  = trim(filter_var($_POST['temperature'],FILTER_VALIDATE_FLOAT));
    $comHum   = trim(filter_var($_POST['humidity'],FILTER_VALIDATE_FLOAT));
    $comBatt  = trim(filter_var($_POST['battery'],FILTER_VALIDATE_FLOAT));

    if(!$comID)
    {
        $inf = "Nieprawidłowy format numeru id\n";
        fwrite($f_hand,$inf);
        fclose($f_hand);
        die("Nieprawidłowy format numeru id");
    }
    if(!$comTemp)
    {
        $inf = "Nieprawidłowy format temperatury\n";
        fwrite($f_hand,$inf);
        fclose($f_hand);
        die("Nieprawidłowy format temperatury");
    }
    if(!$comHum)
    {
        $inf = "Nieprawidłowy format wilgotności\n";
        fwrite($f_hand,$inf);
        fclose($f_hand);
        die("Nieprawidłowy format wilgotności");
    }
    if(!$comBatt)
    {
        $inf = "Nieprawidłowy format poziomu baterii\n";
        fwrite($f_hand,$inf);
        fclose($f_hand);
        die("Nieprawidłowy format poziomu baterii");
    }





    try
    {
        require_once 'config_db.php';

        //checking if sensor is registered in the database
        $sensCheck = $dbLink->prepare("SELECT programowy_nr FROM czujnik WHERE programowy_nr = :parID");
        $sensCheck->bindParam(':parID',$comID, PDO::PARAM_INT);
        $sensCheck->execute();

        if(!$sensCheck->rowCount()>0 || !$sensCheck->fetch()==$comID)
        {
            $inf = "Brak czujnika o podanym numerze w bazie danych\n";
            fwrite($f_hand,$inf);
            fclose($f_hand);
            unset($dbLink);
            unset($sensCheck);
            die("Brak czujnika o podanym numerze w bazie danych");
        }
        unset($sensCheck);

        //Checking state of masures for alarming values
        $alarm = false;
        $isTemp = false;
        $isHum = false;
        $isBatt = false;
        $TempHigh = false;
        $HumHigh = false;
        if($comBatt < BATTERY_MIN)
        {
            $alarm=true;
            $isBatt = true;
        }
        if($comTemp < TEMP_MIN)
        {
            $alarm=true;
            $isTemp = true;
        }
        elseif($comTemp < TEMP_MAX)
        {
            $alarm=true;
            $isTemp = true;
            $TempHigh = true;
        }
        if($comHum < HUM_MIN)
        {
            $alarm=true;
            $isHum = true;
        }
        elseif($comHum < HUM_MAX)
        {
            $alarm=true;
            $isHum = true;
            $HumHigh = true;
        }

        if($alarm)
            sendAlarm($comID,$comTemp,$comHum,$comBatt,$isTemp,$TempHigh,$isHum,$HumHigh,$isBatt);

        $dbLink->beginTransaction();

        $inf = "Połączono z bazą danych\n";
        fwrite($f_hand, $inf);

        /** Battery state update */
        $qry = $dbLink->prepare("UPDATE czujnik
                            SET bateria=:comBatt
                            WHERE id =:comID");

        $qry->bindParam(':comID', $comID, PDO::PARAM_INT);
        $qry->bindParam(':comBatt', $comBatt, PDO::PARAM_STR);

        $qry->execute();
        $dbLink->commit();
        unset($qry);

        /** insert measure into table */
        $dbLink->beginTransaction();
        $qry = $dbLink->prepare("INSERT INTO pomiar (nr_czujnika, wilgotnosc, temperatura) 
                                            VALUES (:comID, :comHum, :comTemp)");

        $qry->bindParam(':comID', $comID, PDO::PARAM_INT);
        $qry->bindParam(':comHum', $comHum, PDO::PARAM_STR);
        $qry->bindParam(':comTemp', $comTemp, PDO::PARAM_STR);

        $qry->execute();
        $dbLink->commit();
        $inf = "Dodano wartości do bazy.\n";
        fwrite($f_hand, $inf);



    } catch (PDOException $e)
    {
        echo $e->getMessage();
        $dbLink->rollBack();
        $inf = "Błąd: ". $e->getMessage() . "\n";
        fwrite($f_hand, $inf);
    }
    catch (Exception $E)
    {
        echo $E->getMessage();
        $inf = "Błąd: ". $E->getMessage() . "\n";
        fwrite($f_hand, $inf);
    }
    unset($qry);
    unset($dbLink);
}
fclose($f_hand);