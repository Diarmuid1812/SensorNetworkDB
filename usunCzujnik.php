<?php
function deleteSensor($num_id)
{


    try{
        require "config_db.php";

        echo 'Connected to database<br>';

        /** @var $dbLink PDO */
        $sql = "SELECT id, programowy_nr FROM czujniki WHERE programowy_nr = :num_id";

        if ($stmt = $dbLink->prepare($sql))
        {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":num_id", $num_id, PDO::PARAM_INT);
            if ($stmt->execute())
            {
                $dbLink->beginTransaction();

                // Check if sensor exists, if yes then verify password
                if ($stmt->rowCount() == 1)
                {
                    $dbLink->exec("SET SQL_SAFE_UPDATES=0");

                    $qry = $dbLink->prepare("DELETE FROM czujniki WHERE programowy_nr=:id");

                    $qry->bindParam(':id', $num_id, PDO::PARAM_INT);

                    $qry->execute();

                    /*** closing connection ***/
                    $dbLink->exec("SET SQL_SAFE_UPDATES=1");
                    $dbLink->commit();
                    unset($dbLink);
                    echo "Usunięto czujnik.<br>";
                    return true;
                } else
                {
                    echo "Brak czujnika o podanej nazwie";
                    $dbLink->rollBack();
                    unset($dbLink);
                    return false;
                }
            }
        }
    }
    catch(PDOException $e)
    {
        $dbLink->rollBack();
        echo $e->getMessage();
        unset($dbLink);
        return false;
    }
}