<?php

/**
 * @param $id
 * @param $program_id
 * @param $miejsce
 * @return bool
 */
function dodajCzujnik($id,$program_id,$miejsce)
{
    # Połączenie
    $link = mysqli_connect("localhost", "root", "", "czujniki");
    if($link === false){
        echo("ERROR: Could not connect. " . mysqli_connect_error());
        return false;
    }

    echo "Connect Successfully. Host info: " . mysqli_get_host_info($link);

    mysqli_query($link,"SET NAMES 'utf8'");
    $sql = "INSERT INTO czujnik VALUES (1, 1, 87, 'Za sałatą (test dodania czujnika)')";
    if(!mysqli_query($link, $sql)){
        //todo:obsługa błędu
    }

    mysqli_close($link);
    return true;
}