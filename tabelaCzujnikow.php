<?php
/**!
 *  Code to display a table of sensors, loaded from MySQL Database
 */

/*todo:
 *  (!)use for currently logged in user only,
 *  (!)delete "root"
 *  (?)PDO
*/

$linkSensTable=mysqli_connect("localhost:3306","root","","czujniki");
/*
if(!$linkSensTable)
{
todo: (?)error handling
}
*/

mysqli_query($linkSensTable,"SET NAMES 'utf8'");
$tableMapSensors=mysqli_query($linkSensTable,"SELECT * FROM czujnik");

echo "<table>
    <tr>
    <th>id</th>
    <th>programowy_nr</th>
    <th>bateria</th>
    <th>miejsce</th>
    ";

while ($rowSensors=mysqli_fetch_array($tableMapSensors))
{
    echo "<tr>";
    echo "<td>". $rowSensors["id"] . "</td>";
    echo "<td>". $rowSensors["programowy_nr"] . "</td>";
    echo "<td>". $rowSensors["bateria"] . "</td>";
    echo "<td>". $rowSensors["miejsce"] . "</td>";
}
