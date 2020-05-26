<?php

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: usrLogin.php");
    exit;
}


if(isset($_POST["dateStart"])&&isset($_POST["dateEnd"]))
{
    $dateStart=$_POST["dateStart"];
    $dateEnd=$_POST["dateEnd"];
}
else
{
    if (isset($_POST["yesterday"]))
    {
        $dateEnd = date("Y-m-d");
        $dateStart = date("Y-m-d",
            mktime(
                0,
                0,
                0,
                date("m"),
                date("d") - 1,
                date("Y")));
    }
    else
    {
        $months = 0;
        $days = 0;
        $years = 0;

        if (isset($_POST["lastYear"]))
            $years = 1;
        elseif (isset($_POST["lastMonth"]))
            $months = 1;
        elseif (isset($_POST["lastWeek"]))
            $days = 7;
        elseif (isset($_POST["lastDay"]))
            $days = 0;
        else
            $months = 1;

        $dateEnd = date("Y-m-d H:i:s");
        $dateStart = date("Y-m-d",
            mktime(
                0,
                0,
                0,
                date("m") - $months,
                date("d") - $days,
                date("Y") - $years));
    }
}

try{
    require_once "config_db.php";

    $report = array();

    $qrySens="SELECT * FROM czujniki";
    foreach ( $dbLink->query($qrySens) as $rowSens)
    {
        $paramID = $rowSens["programowy_nr"];
        $qryMeas= $dbLink->prepare("SELECT * FROM pomiar WHERE nr_czujnika=:sensID
                       AND data BETWEEN :dateStart AND :dateEnd");
        $qryMeas->bindParam(':dateStart', $dateStart, PDO::PARAM_STR);
        $qryMeas->bindParam(':dateEnd', $dateEnd, PDO::PARAM_STR);
        $qryMeas->bindParam(':sensID', $paramID, PDO::PARAM_INT);

        $qryMeas->execute();

        foreach ($qryMeas as $rowMeas)
        {
            array_push($report,
                array("nr_czujnika" => $rowMeas["nr_czujnika"],
                    "data" => $rowMeas["data"],
                    "wilgotnosc" =>number_format($rowMeas["wilgotnosc"],2),
                    "temperatura" =>number_format($rowMeas["temperatura"],2)));
        }
        if(isset($_POST["generate"])&&$_POST["generate"]==="gen")
        {

            $fp = fopen('reports/sample.csv', 'wb');
            foreach ($report as $rowMeas)
            {
                $info = array($rowMeas["nr_czujnika"], $rowMeas["data"], $rowMeas["wilgotnosc"], $rowMeas["temperatura"]);
                fputcsv($fp, $info);
            }
            fclose($fp);

            header("Content-Description: File Transfer");
            header('Content-Disposition: attachment; filename="raport.csv"');
            header('Content-Type: text/csv');
            readfile("reports/sample.csv");
            die();
        }
    }


    unset($dbLink);




}
catch(PDOException $e)
{
    echo "Błąd:".$e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="pl">
<link rel="stylesheet" type="text/css" href="teststyl.css">
<head>
    <meta charset="UTF-8">
    <title>Raporty</title>
</head>
<body>

	<div class="header"><h1>Raport</h1></div>
		<div class="column navig">
				<ul>
					<li><a href="interfejsGlowny.phtml">Strona główna</a></li>
					<li><a href="genReport.php">Raporty</a></li> <!-- domyslna strona po zalogowaniu -->
					<li><a href="interfejsCzujniki.php">Zarządzaj czujnikami</a></li>
					<li><a href="#">Zarządzaj użytkownikami</a></li>
					<li><a href="setResetPassword.php">Zresetuj hasło</a></li>
					<li><a href="usrLogout.php">Wyloguj się</a></li>
				</ul>
		</div>
		
		<div class="column content">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div class="container">
					<div class="form-group <?php echo (!empty($dateStartErr)) ? 'has-error' : ''; ?>">
						<label>Od:
							<input type="date" name="dateStart" class="form-control" value="<?php echo $dateStart; ?>">
						</label>
						<span class="help-block"><?php echo isset($dateStartErr); ?></span>
					</div>
					<div class="form-group <?php echo (!empty($dateEndErr)) ? 'has-error' : ''; ?>">
						<label>Do:
							<input type="date" name="dateEnd" class="form-control" value="<?php echo date("Y-m-d",strtotime($dateEnd)); ?>">
						</label>
						<span class="help-block"><?php echo isset($dateEndErr); ?></span>
					</div>
			<br>
					<div class="form-group">
						<input type="submit" class="myButton" value="Zmień">
						<input type="reset" class="myButton2">
					</div>
				</div>
			</form>
			
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div class="form-group">
					<input type="submit" name="lastYear" value="Z ostatniego roku">
					<input type="submit" name="lastMonth" value="Z ostatniego miesiąca">
					<input type="submit" name="lastWeek" value="Z ostatniego tygodnia">
					<input type="submit" name="yesterday" value="Z wczoraj">
					<input type="submit" name="lastDay" value="Z dzisiaj">
				</div>
			</form>
			
			<br>
			
				<table>
					<thead>
						<tr>
							<th>Nr czujnika</th>
							<th>Data pomiaru</th>
							<th>Wilgotność</th>
							<th>Temperatura</th>
						</tr>
					</thead>
					
					<tbody>
						<?php
						foreach ($report as $rowTable)
						{
							echo "<tr>".
								"<td>".$rowTable['nr_czujnika']."</td>".
								"<td>".$rowTable['data']."</td>".
								"<td>".$rowTable['wilgotnosc']."</td>".
								"<td>".$rowTable['temperatura']."</td>".
								"</tr>";
						}
						?>
					</tbody>
				</table>

			<br>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					<input type="hidden" name="generate" value="gen" />
					<input type="hidden" name="dateStart" value="<?php echo $dateStart; ?>" />
					<input type="hidden" name="dateEnd" value="<?php echo date("Y-m-d", strtotime($dateEnd)); ?>" />
					<input type="submit" class="myButton" name="gen" value="Generuj raport">
					<input type="button" class="myButton2" onclick="location='interfejsGlowny.phtml'" value="Powrót">
					
				</form>
			<br>
			
		</div>
</body>
</html>

