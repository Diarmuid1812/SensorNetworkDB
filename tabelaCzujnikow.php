<?php
/**!
 *  Code to display a table of sensors, loaded from MySQL Database
 */

/*todo:
 *  (!)use for currently logged in user only,
 *  (!)delete "root"
 *  (?)PDO
*/

/** Data */

$host = 'localhost';
/*todo: Set to logged user*/
$username = 'root';
$password = '';




try
{
    $dbLink = new PDO("mysql:host=$host;dbname=czujniki;charset=utf8", $username, $password);

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
    $dbh = null;
}
catch (PDOException $e)
{
    echo $e->getMessage();
}