<?php
/**!
 *  Code to display a table of sensors, loaded from MySQL Database
 */

/** Data */




try
{
    require "config_db.php";

    $qry = "SELECT * FROM czujniki";

    echo "<table>
    <caption> <h3>Tabela czujników </h3></caption>
    <tr>
    <th>id</th>
    <th>programowy_nr</th>
    <th>bateria</th>
    <th>miejsce</th>
    </tr>";

    foreach ( $dbLink->query($qry) as $rowSensors)
    {
        echo "<tr>";
        echo "<td>". $rowSensors["nr_czujnika"] . "</td>";
        echo "<td>". $rowSensors["prog_nr"] . "</td>";
        echo "<td>". $rowSensors["bateria"] . "</td>";
        echo "<td>". $rowSensors["miejsce"] . "</td></tr>";



    }
    echo "</table>";
    unset($dbLink);
}
catch (PDOException $e)
{
    echo $e->getMessage();
}