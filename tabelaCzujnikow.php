<?php
/**!
 *  Code to display a table of sensors, loaded from MySQL Database
 */

/** Data */




try
{
    require_once "config_db.php";

    $qry = "SELECT * FROM czujnik";

    echo "<table>
    <tr>
    <th>id</th>
    <th>programowy_nr</th>
    <th>bateria</th>
    <th>miejsce</th>
    ";

    foreach ( $dbLink->query($qry) as $rowSensors)
    {
        echo "<tr>";
        echo "<td>". $rowSensors["id"] . "</td>";
        echo "<td>". $rowSensors["programowy_nr"] . "</td>";
        echo "<td>". $rowSensors["bateria"] . "</td>";
        echo "<td>". $rowSensors["miejsce"] . "</td>";
    }
    $dbLink = null;
}
catch (PDOException $e)
{
    echo $e->getMessage();
}