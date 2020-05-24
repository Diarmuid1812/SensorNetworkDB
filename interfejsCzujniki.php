<?php
/** login test */
// Initialize the session
session_start();
/*
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: usrLogin.php");
    exit;
}*/
?>



<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" type="text/css" href="teststyl.css">
<head>
    <meta charset="UTF-8">
    <title>Zarządzaj_czujnikami </title>

</head>
<body>
	<div class="header"><h1>Zarządzaj czujnikami</h1></div>
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
	</div>
	
	<div class="column content">
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div class="container">
					<div class="form-group <?php echo (!empty($dateStartErr)) ? 'has-error' : ''; ?>">
						<label>Dodaj
							<input type="number" name="prog_nr" class="form-control" value="<?php/* echo $;*/ ?>">
						</label>
						<span class="help-block"><?php echo isset($dateStartErr); ?></span>
					</div>
					<div class="form-group <?php echo (!empty($dateEndErr)) ? 'has-error' : ''; ?>">
						<label>
							<input type="text" name="miejsce" class="form-control" value="<?php echo $dateEnd; ?>">
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
					<input type="hidden" name="dateEnd" value="<?php echo $dateEnd; ?>" />
					<input type="submit" class="myButton" name="gen" value="Generuj raport">
					<input type="button" class="myButton2" onclick="location='interfejsGlowny.phtml'" value="Powrót">
					
				</form>
			<br>
				
	</div>
	

</body>
</html>