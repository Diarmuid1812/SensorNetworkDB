<?php

/**
 *  Code to display a table of measures, loaded from MySQL Database
 *  Displays measures from last 24 hours
 */



try
{
    require "config_db.php";

    $qry = "SELECT * FROM pomiar WHERE data > DATE_ADD(CURDATE(), INTERVAL -1 DAY)";

    echo "<table>
    <caption> <h3>Tabela pomiarów </h3></caption>
    <tr>
    
    <th>Nr czujnika</th>
    <th>Data</th>
    <th>Wilgotność [%]</th>
    <th>Temperatura [&deg;C]</th>
    </tr>"; //<th>id</th>


    foreach ( $dbLink->query($qry) as $rowPomiar)
    {
        echo "<tr>";
        //echo "<td>" . $rowPomiar["id"] . "</td>";
        echo "<td>" . $rowPomiar["nr_czujnika"] . "</td>";
        echo "<td>" . $rowPomiar["data"] . "</td>";
        echo "<td>" . $rowPomiar["wilgotnosc"] . "</td>";
        echo "<td>" . $rowPomiar["temperatura"] . "</td></tr>";

    }
    echo "</table>";
    unset($dbLink);

}
catch (PDOException $e)
{
    echo $e->getMessage();
}