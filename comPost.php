<?php

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

    $comID    = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $comTemp  = filter_var($_POST['temperature'],FILTER_VALIDATE_FLOAT);
    $comHum   = filter_var($_POST['humidity'],FILTER_VALIDATE_FLOAT);
    $comBatt  = filter_var($_POST['battery'],FILTER_VALIDATE_FLOAT);

//todo: (!)sprawdzanie nr czujnika z bazą,
//      (!)obsługa błędów

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

        $inf = "Połączono z bazą danych\n";
        fwrite($f_hand, $inf);

        /** Battery state update */
        $qry = $dbLink->prepare("UPDATE czujnik
                            SET bateria=:comBatt
                            WHERE id =:comID");

        $qry->bindParam(':comID', $comID, PDO::PARAM_INT);
        $qry->bindParam(':comBatt', $comBatt, PDO::PARAM_STR);

        $qry->execute();

        unset($qry);

        /** insert measure into table */
        $qry = $dbLink->prepare("INSERT INTO pomiar (nr_czujnika, wilgotnosc, temperatura) 
                                            VALUES (:comID, :comHum, :comTemp)");

        $qry->bindParam(':comID', $comID, PDO::PARAM_INT);
        $qry->bindParam(':comHum', $comHum, PDO::PARAM_STR);
        $qry->bindParam(':comTemp', $comTemp, PDO::PARAM_STR);

        $qry->execute();

        $inf = "Dodano wartości do bazy.\n";
        fwrite($f_hand, $inf);



    } catch (PDOException $e)
    {
        echo $e->getMessage();
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