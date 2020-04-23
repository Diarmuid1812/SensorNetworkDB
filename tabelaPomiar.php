<?php

/**!
 *  Code to display a table of measures, loaded from MySQL Database
 */

/*todo:
 *  (!)use for currently logged in user only,
 *  (!)delete "root"
 *  (?)PDO
*/

$linkSensTable = mysqli_connect("localhost:3306", "root", "", "czujniki");
/*
if(!$linkSensTable)
{
todo: (?)error handling
}
*/

mysqli_query($linkSensTable, "SET NAMES 'utf8'");
$tableMapPomiar = mysqli_query($linkSensTable, "SELECT * FROM pomiar");

echo "<table>
    <tr>
    <th>id</th>
    <th>Nr czujnika</th>
    <th>Data</th>
    <th>Wilgotność</th>
    <th>Temperatura</th>
    ";

while ($rowPomiar = mysqli_fetch_array($tableMapPomiar)) {
    echo "<tr>";
    echo "<td>" . $rowPomiar["id"] . "</td>";
    echo "<td>" . $rowPomiar["nr_czujnika"] . "</td>";
    echo "<td>" . $rowPomiar["data"] . "</td>";
    echo "<td>" . $rowPomiar["wilgotnosc"] . "</td>";
    echo "<td>" . $rowPomiar["temperatura"] . "</td>";
}