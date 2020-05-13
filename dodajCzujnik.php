<?php

/**
 * @param $id
 * @param $program_id
 * @param $miejsce
 * @return bool
 */
function addSensor($id,$program_id,$miejsce)
{

    /**
     * todo (!)obsługa wyjątku
     */

    if(! filter_var($id,FILTER_VALIDATE_INT) || !filter_var($program_id,FILTER_VALIDATE_INT))
    {
        echo "Nieprawidłowy numer id";
        return false;
    }

    if(! filter_var($miejsce, FILTER_VALIDATE_REGEXP,
    array("options"=>array("regexp"=>'/^[a-zA-ZąćęłńóśżźĄĆĘŁŃÓŚŻŹ _]{3,100}$/')))){
        echo "Nieprawidłowy opis miejsca";
        return false;
    }

    try{
        /*** link data ***/
        $hostname = 'localhost';
        $username = 'root';
        /*** Set password*/
        $passwd   = '';
        $database = 'czujniki';

        $dbLink = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $passwd);
        echo 'Connected to database\n';

        /***error reporting attribute ***/
        $dbLink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $qry = $dbLink->prepare("INSERT INTO czujnik VALUES (:id, :program_id, 0, :miejsce)");

        /*** bind the paramaters ***/
        $qry->bindParam(':id', $id, PDO::PARAM_INT);
        $qry->bindParam(':program_id', $program_id, PDO::PARAM_INT);
        $qry->bindParam(':miejsce', $miejsce, PDO::PARAM_STR, 100);

        $qry->execute();

        /*** closing connection ***/
        $dbLink = null;
        echo 'Sensor added successfully.\n';
        return true;
    }
catch(PDOException $e)
    {
        echo $e->getMessage();
        return false;
        //todo: throw, handling
    }
 }