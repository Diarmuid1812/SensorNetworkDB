<?php

/**!
 *  Code to display a table of measures, loaded from MySQL Database
 */

/*todo:
 *  (!)use for currently logged in user only,
 *  (!)delete "root"
*/

/** Data */

$hostname = 'localhost';
/*todo: Set to logged user*/
$username = 'root';
$passwd = '';
$database = 'czujniki';

try
{


    $dbLink = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8", $username, $passwd);

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