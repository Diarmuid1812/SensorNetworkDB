<?php
function deleteSensor($num_id)
{


    try{
        require "config_db.php";

        echo 'Connected to database<br>';

        /** @var $dbLink PDO */
        $dbLink->beginTransaction();
        $dbLink ->exec("SET SQL_SAFE_UPDATES=0");

        $qry = $dbLink->prepare("DELETE FROM czujnik WHERE id=:id");

        $qry->bindParam(':id', $num_id, PDO::PARAM_INT);

        $qry->execute();

        /*** closing connection ***/
        $dbLink ->exec("SET SQL_SAFE_UPDATES=1");
        $dbLink->commit();
        echo "Records were deleted successfully.<br>";
        return true;
    }
    catch(PDOException $e)
    {
        $dbLink->rollBack();
        echo $e->getMessage();
        return false;

    }

}