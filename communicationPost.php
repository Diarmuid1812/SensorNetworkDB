<?php
$comNrPom= $_POST['nrPom'];
$comDate = $_POST['date'];
$comID   = $_POST['id'];
$comTemp = $_POST['temperature'];
$comHum  = $_POST['humidity'];
$comBatt = $_POST['battery'];

//todo: (!)sanitityzacja,
//      (!)sprawdzanie nr czujnika z bazą,
//      (!)obsługa błędów
//      (!)powiązanie z systemem logowania
//      (?)ustalanie daty,
//      (?)ustalanie numeru pomiaru

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

