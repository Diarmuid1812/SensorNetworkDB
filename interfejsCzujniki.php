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

$dodaj_prog_nr = "";
$usun_prog_nr = "";
$miejsce = "";
$usun_prog_nr_err = $dodaj_prog_nr_err = $miejsce_err = "";

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
			<div class="container">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					
						<p>Dodaj</p>
						<div class="form-group <?php echo (!empty($dodaj_prog_nr_err)) ? 'has-error' : ''; ?>">
							<label>Nr programowy
								<input type="number" name="prog_nr" class="form-control" value="<?php echo $dodaj_prog_nr; ?>">
							</label>
							<span class="help-block"><?php echo isset($dateStartErr); ?></span>
						</div>
						
						<div class="form-group <?php echo (!empty($miejsce_err)) ? 'has-error' : ''; ?>">
							<label>Miejsce
								<input type="text" name="miejsce" class="form-control" value="<?php echo $miejsce; ?>">
							</label>
							<span class="help-block"><?php echo isset($dateStartErr); ?></span>
						</div>
						
						<input type="submit" class="myButton" name="dodaj_butt" value="Zatwierdź">
				</form>
				
					<br>
					
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">	
						<p>Usuń</p>
						<div class="form-group <?php echo (!empty($usun_prog_nr_err)) ? 'has-error' : ''; ?>">
							<label>Nr programowy
								<input type="number" name="prog_nr" class="form-control" value="<?php echo $usun_prog_nr; ?>">
							</label>
							<span class="help-block"><?php echo isset($dateStartErr); ?></span>
						</div>
						
						<input type="submit" class="myButton" name="usun_butt" value="Zatwierdź">
						
					
				</form>		
			</div>
			
			<br>
			
			<?php include "tabelaCzujnikow.php"?>
				
	</div>
	

</body>
</html>