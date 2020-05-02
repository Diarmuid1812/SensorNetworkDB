<?php
function deleteSensor($num_id)
{


    try{
        /*** link data ***/
        $hostname = 'localhost';
        $username = 'root';
        /*** Set password*/
        $passwd   = '';
        $database = 'czujniki';

        $dbLink = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $passwd);
        echo 'Connected to database<br>';

        /***error reporting attribute ***/
        $dbLink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $dbLink ->exec("SET SQL_SAFE_UPDATES=0");

        $qry = $dbLink->prepare("DELETE FROM czujnik WHERE id=:id");

        $qry->bindParam(':id', $num_id, PDO::PARAM_INT);

        $qry->execute();

        /*** closing connection ***/
        $dbLink ->exec("SET SQL_SAFE_UPDATES=1");
        $dbLink = null;
        echo "Records were deleted successfully.\n";
        return true;
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
        return false;
        //todo: throw, handling
    }

}