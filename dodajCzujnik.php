<?php

/**
 * @param $id
 * @param $program_id
 * @param $miejsce
 * @return bool
 */
function addSensor($program_id,$miejsce)
{

    /**
     * todo (!)obsługa wyjątku
     */

    if(!filter_var($program_id,FILTER_VALIDATE_INT))
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

        require "config_db.php";

        echo '<p>Connected to database</p>';
        /** @var $dbLink PDO*/
        $dbLink->beginTransaction();

        $qry = $dbLink->prepare("INSERT INTO czujniki (programowy_nr, bateria, miejsce) VALUES (:program_id, 0, :miejsce)");

        $param_miejsce = trim($miejsce);

        /*** bind the paramaters ***/
        $qry->bindParam(':program_id', $program_id, PDO::PARAM_INT);
        $qry->bindParam(':miejsce', $param_miejsce, PDO::PARAM_STR, 100);

        $qry->execute();
        $dbLink->commit();
        /*** closing connection ***/

        echo 'Sensor added successfully.\n';
        return true;
    }
catch(PDOException $e)
    {
        $dbLink->rollBack();
        echo $e->getMessage();
        return false;
    }
 }