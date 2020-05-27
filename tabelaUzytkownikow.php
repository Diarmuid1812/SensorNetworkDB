<?php
/**!
 *  Code to display a table of users, loaded from MySQL Database
 */

/** Data */

try
{
    require "config_db.php";

    $qry = "SELECT * FROM users order by admin desc";

    echo "<table>
    <caption> <h3>Użytkownicy </h3></caption>
    <tr>
    
    <th>Nr</th>
    <th>Nazwa użytkownika</th>
    <th>Administrator</th>
    </tr>";

    foreach ( $dbLink->query($qry) as $rowSensors)
    {
        echo "<tr>";
        //echo "<td>". $rowSensors["id"] . "</td>";
        echo "<td>". $rowSensors["id"] . "</td>";
        echo "<td>". $rowSensors["username"] . "</td>";
        //echo "<td>". $rowSensors["admin"] . "</td></tr>";
		
		if($rowSensors["admin"] === "1")
		{
			echo "<td>". "tak" . "</td></tr>";
		}
		
		else
		{
			echo "<td>". "nie" . "</td></tr>";
		}



    }
    echo "</table>";
    unset($dbLink);
}
catch (PDOException $e)
{
    echo $e->getMessage();
}