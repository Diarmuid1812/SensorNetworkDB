<?php

/**!
 *  Code to display a table of measures, loaded from MySQL Database
 */



/** Data */



try
{
    require_once "config_db.php";

    $qry = "SELECT * FROM pomiar";

    echo "<table>
    <tr>
    <th>id</th>
    <th>Nr czujnika</th>
    <th>Data</th>
    <th>Wilgotność</th>
    <th>Temperatura</th>
    ";


    foreach ( $dbLink->query($qry) as $rowPomiar)
    {
        echo "<tr>";
        echo "<td>" . $rowPomiar["id"] . "</td>";
        echo "<td>" . $rowPomiar["nr_czujnika"] . "</td>";
        echo "<td>" . $rowPomiar["data"] . "</td>";
        echo "<td>" . $rowPomiar["wilgotnosc"] . "</td>";
        echo "<td>" . $rowPomiar["temperatura"] . "</td>";
    }

    $dbLink=null;

}
catch (PDOException $e)
{
    echo $e->getMessage();
}