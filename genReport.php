<?php

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="reports/sample.csv"');

try{
    require_once "config_db.php";

    echo 'Connected to database\n';

    $dbLink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $qrySens="SELECT * FROM czujnik";
    foreach ( $dbLink->query($qrySens) as $rowSens)
    {
        $paramID = "";
        $qryMeas= $dbLink->prepare("SELECT * FROM pomiar WHERE nr_czujnika=:sensID");
        $qryMeas->bindParam(':sensID', $paramID, PDO::PARAM_INT);
        $paramID = $rowSens["programowy_nr"];

        $fp = fopen('php://output', 'wb');

        foreach ($dbLink->query($qryMeas) as $rowMeas)
        {
            $val =  array($rowMeas["id czujnika"],$rowMeas["data"],$rowMeas["wilgotnosc"],$rowMeas["temperatura"]);
            fputcsv($fp, $val);
        }
        fclose($fp);

    }


    # $qry = $dbLink->prepare("INSERT INTO raport VALUES (:id, :nr_czujnika, )");


    #$qry->bindParam(':id', $id, PDO::PARAM_INT);
    #$qry->bindParam(':program_id', $program_id, PDO::PARAM_INT);
    #$qry->bindParam(':miejsce', $miejsce, PDO::PARAM_STR, 100);

    #$qry->execute();


    unset($dbLink);
    //echo 'added successfully.\n';




}
catch(PDOException $e)
{
    echo $e->getMessage();
    return false;
}


