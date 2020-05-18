<?php

//header('Content-Type: text/csv');
//header('Content-Disposition: attachment; filename="reports/sample.csv"');

//time todo: get form _POST

$months = 1;
$days   = 0;
$years  = 0;

$dateEnd = date("Y-m-d");
$dateStart = date("Y-m-d",
    mktime(
        date("H"),
        date("i"),
        date("s"),
        date("m")-$months,
        date("d")-$days,
        date("Y")-$years));

try{
    require_once "config_db.php";

    echo 'Connected to database\n';

    $qrySens="SELECT * FROM czujnik";
    foreach ( $dbLink->query($qrySens) as $rowSens)
    {
        $paramID = $rowSens["programowy_nr"];
        $qryMeas= $dbLink->prepare("SELECT * FROM pomiar WHERE nr_czujnika=:sensID
                       AND data BETWEEN :dateStart AND :dateEnd");
        $qryMeas->bindParam(':dateStart', $dateStart, PDO::PARAM_STR);
        $qryMeas->bindParam(':dateEnd', $dateEnd, PDO::PARAM_STR);
        $qryMeas->bindParam(':sensID', $paramID, PDO::PARAM_INT);

        //$fp = fopen('php://output', 'wb');

        //Row number
        $iX = 1; //reset to 1;

        foreach ($dbLink->query($qryMeas) as $rowMeas)
        {
            //$val =  array($rowMeas["id czujnika"],$rowMeas["data"],$rowMeas["wilgotnosc"],$rowMeas["temperatura"]);
            //fputcsv($fp, $val);
/*
            $qry = $dbLink->prepare("INSERT INTO raport (id, nr_czujnika, miejsce, data, wilgotnosc, temperatura)
                                        VALUES (:iX, :sensID, :place, :date, :param_hum,:param_temp)");

            $place=trim($rowMeas["miejsce"]);
            $date =trim($rowMeas["data"]);
            $param_hum=trim($rowMeas["wilgotnosc"]);
            $param_temp=trim($rowMeas["temperatura"]);

            $qry->bindParam(':iX', $iX, PDO::PARAM_INT);
            $qry->bindParam(':sensID', $paramID, PDO::PARAM_INT);
            $qry->bindParam(':place', $place, PDO::PARAM_STR, 100);
            $qry->bindParam(':date', $date, PDO::PARAM_STR, 30);
            $qry->bindParam(':param_hum', $param_hum, PDO::PARAM_INT);
            $qry->bindParam(':param_temp', $param_temp, PDO::PARAM_INT);

            $qry->execute();*/
            ++$iX;
        }
        //fclose($fp);

    }

    unset($dbLink);




}
catch(PDOException $e)
{
    echo $e->getMessage();
    return false;
}


