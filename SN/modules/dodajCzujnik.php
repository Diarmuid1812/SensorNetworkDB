<?php

/**
 * @param $program_id
 * @param $miejsce
 * @return bool
 */
function addSensor($program_id,$miejsce)
{

    $finished = "";

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
        unset($dbLink);
        unset($qry);


        $finished = ""; //Success
    }
catch(PDOException $e)
    {
        $dbLink->rollBack();
        $finished = "Błąd połączenia z bazą. Sprawdź czy wpraowadziłeś poprawny numer czujnika.";
        throw $e;
    }

finally
    {
        return $finished;
    }
 }