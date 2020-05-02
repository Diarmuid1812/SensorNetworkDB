<?php
$comNrPom= $_POST['nrPom'];
$comDate = $_POST['date'];
$comID   = $_POST['id'];
$comTemp = $_POST['temperature'];
$comHum  = $_POST['humidity'];
$comBatt = $_POST['battery'];

//todo: (!)sprawdzanie nr czujnika z bazą,
//      (!)obsługa błędów
//      (!)powiązanie z systemem logowania
//      (?)ustalanie daty,
//      (?)ustalanie numeru pomiaru

if(! filter_var($comNrPom, FILTER_VALIDATE_INT))
{
    die("Nieprawidłowy format numery pomiaru");
}
if(! filter_var($comDate, FILTER_VALIDATE_REGEXP,
    array("options"=>array("regexp"=>'/^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-[0-9]{4}$/'))))
{
    die("Nieprawidłowy format daty");
}
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




$hostname = 'localhost';
/*** Set password*/
$username = 'root';
$passwd = '';
$database = 'czujniki';
/*
$link = mysqli_connect("localhost", "root", "", "czujniki");
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

mysqli_query($link,"SET NAMES 'utf8'");
$sql = "SELECT bateria 
        FROM czujnik
        where id =".$comID;
if(!mysqli_query($link, $sql)){
    //todo:obsługa błędu
}

mysqli_query($link,"SET NAMES 'utf8'");
$sql = "INSERT 
        INTO pomiar(  id          , nr_czujnika,  data      ,  temperatura,  wilgotnosc)
        VALUES     (".$comNrPom.",".$comID.   ",".$comDate.",".$comTemp ."," .$comHum.");";
if(!mysqli_query($link, $sql)){
    //todo:obsługa błędu
}


mysqli_close($link);
*/
try
{
    $dbLink = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $passwd);

    /*** Battery state update */
    $qry = $dbLink->prepare( "UPDATE czujnik
                            SET bateria=:comBatt
                            WHERE id =:comID");

    $qry->bindParam(':comID', $comID, PDO::PARAM_INT);
    $qry->bindParam(':comBatt', $comBatt, PDO::PARAM_INT);

    $qry->execute();

    $qry = $dbLink->prepare("INSERT INTO pomiar VALUES (:comNrPom, :comID, :comDate, :comHum, :comTemp)");

    /*** bind the paramaters ***/
    $qry->bindParam(':comNrPom', $comNrPom, PDO::PARAM_INT);
    $qry->bindParam(':comID', $comID, PDO::PARAM_INT);
    $qry->bindParam(':comDate', $comDate, PDO::PARAM_STR,11);
    $qry->bindParam(':comHum', $comHum, PDO::PARAM_INT);
    $qry->bindParam(':comTemp', $comTemp, PDO::PARAM_INT);

    $qry->execute();

    $dbLink = null;
}

catch (PDOException $e)
{
    echo $e->getMessage();
}
