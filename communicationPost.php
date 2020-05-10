<?php
$comNrPom= $_POST['nrPom'];
$comID   = $_POST['id'];
$comTemp = $_POST['temperature'];
$comHum  = $_POST['humidity'];
$comBatt = $_POST['battery'];

//todo: (!)sprawdzanie nr czujnika z bazą,
//      (!)obsługa błędów
//      (!)ustalanie numeru pomiaru

if(! filter_var($comNrPom, FILTER_VALIDATE_INT))
{
    die("Nieprawidłowy format numery pomiaru");
}
/*if(! filter_var($comDate, FILTER_VALIDATE_REGEXP,
    array("options"=>array("regexp"=>'/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/'))))
{
    die("Nieprawidłowy format daty");
}*/
if(! filter_var($comID,FILTER_VALIDATE_INT))
{
    die("Nieprawidłowy format numeru id");
}
if(! filter_var($comTemp,FILTER_VALIDATE_INT))
{
    die("Nieprawidłowy format temperatury");
}
if(! filter_var($comHum,FILTER_VALIDATE_INT))
{
    die("Nieprawidłowy format wilgotności");
}
if(! filter_var($comBatt,FILTER_VALIDATE_INT))
{
    die("Nieprawidłowy format poziomu baterii");
}




try
{
    require_once 'config_db.php';

    /** Battery state update */
    $qry = $dbLink->prepare( "UPDATE czujnik
                            SET bateria=:comBatt
                            WHERE id =:comID");

    $qry->bindParam(':comID', $comID, PDO::PARAM_INT);
    $qry->bindParam(':comBatt', $comBatt, PDO::PARAM_INT);

    $qry->execute();

    /** insert measure int table */
    $qry = $dbLink->prepare("INSERT INTO pomiar (id, nr_czujnika, wilgotnosc, temperatura) 
                                            VALUES (:comNrPom, :comID, :comHum, :comTemp)");

    $qry->bindParam(':comNrPom', $comNrPom, PDO::PARAM_INT);
    $qry->bindParam(':comID', $comID, PDO::PARAM_INT);
    $qry->bindParam(':comHum', $comHum, PDO::PARAM_INT);
    $qry->bindParam(':comTemp', $comTemp, PDO::PARAM_INT);

    $qry->execute();

    $dbLink = null;
}

catch (PDOException $e)
{
    echo $e->getMessage();
}
