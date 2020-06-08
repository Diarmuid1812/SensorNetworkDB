<?php
/** login test */
// Initialize the session
session_start();

$dodaj_prog_nr = "";
$usun_prog_nr = "";
$miejsce = "";
$usun_prog_nr_err = $dodaj_prog_nr_err = $miejsce_err = "";

//handle deletion
if (isset($_POST["usun_prog_nr"])) //if sent from form
    $usun_prog_nr = $_POST["usun_prog_nr"];

elseif(isset($_SESSION["POST"])) //if validated
{
    $temp = $_SESSION["POST"];
    unset($_SESSION["POST"]);
    $usun_prog_nr = $temp["usun_prog_nr"];
}

unset($_SESSION["val_kind"]);

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: usrLogin.php");
    exit;
} elseif (!isset($_SESSION["permission"])||$_SESSION["permission"]!==true)
{
    header("location: interfejsGlowny.php");
    exit;
}





require_once 'modules/dodajCzujnik.php';
require_once 'modules/usunCzujnik.php';
?>



<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" type="text/css" href="CSS/teststyl.css">
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
            <?php
                if(isset($_SESSION["permission"])&&$_SESSION["permission"]===true)
                {
                    echo '<li><a href="interfejsCzujniki.php">Zarządzaj czujnikami</a></li>';
				}
                if(isset($_SESSION["permission"])&&$_SESSION["permission"]===true)
                {
                    echo '<li><a href="addUser.php">Zarządzaj użytkownikami</a></li>';
                }
            ?>
            <li><a href="setResetPassword.php">Zresetuj hasło</a></li>
            <li><a href="usrLogout.php">Wyloguj się</a></li>
        </ul>
    </div>
	
	<div class="column content">
			<div class="container">
				<!-- dodawanie czujnikow -->
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
					
						<p>Dodaj czujnik</p>
						<div class="form-group <?php echo (!empty($dodaj_prog_nr_err)) ? 'has-error' : ''; ?>">
							<label>Nr programowy
								<input type="text" name="prog_nr" class="form-control" value="<?php echo $dodaj_prog_nr; ?>">
							</label>
							<span class="help-block"><?php echo $dodaj_prog_nr_err; ?></span>
						</div>
						
						<div class="form-group <?php echo (!empty($miejsce_err)) ? 'has-error' : ''; ?>">
							<label>Miejsce
								<input type="text" name="miejsce" class="form-control" value="<?php echo $miejsce; ?>">
							</label>
							<span class="help-block"><?php echo $miejsce_err; ?></span>
						</div>
						
						<input type="submit" class="myButton" name="dodaj_butt" value="Zatwierdź">
						
				</form>
				<div>
					<?php
						if(isset($_POST['prog_nr'])&&isset($_POST['miejsce']))
						{
                            if(!filter_var($_POST['prog_nr'],FILTER_VALIDATE_INT))
                            {

                                $dodaj_prog_nr_err = "Nieprawidłowy numer id";;
                            }

                            if(!filter_var($_POST['miejsce'], FILTER_VALIDATE_REGEXP,
                                array("options"=>array("regexp"=>'/^[a-zA-ZąćęłńóśżźĄĆĘŁŃÓŚŻŹ0-9 _]{3,100}$/')))){
                                $finished = "Nieprawidłowy opis miejsca. Opis powinien składać się z od 3 do 100 liter i cyfr";;
                            }
						    if(empty($dodaj_prog_nr_err))
                            {
                                $dodaj_prog_nr = $_POST['prog_nr'];
                                $miejsce = $_POST['miejsce'];

                                if (!empty($dodaj_prog_nr) && !empty($miejsce))
                                {
                                    $dodaj_prog_nr_err = addSensor($dodaj_prog_nr, $miejsce);
                                }
                                if(empty($dodaj_prog_nr_err))
                                {   /* zapobiega dodawaniu wpisu po odswierzeniu strony*/
                                    Header("Location: interfejsCzujniki.php");
                                }
                            }
						}
					?>
				</div>
					<br>
					<!-- usuwanie czujnikow -->
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">	
						<p>Usuń czujnik</p>
						<div class="form-group <?php echo (!empty($usun_prog_nr_err)) ? 'has-error' : ''; ?>">
							<label>Nr programowy
								<input type="number" name="usun_prog_nr" class="form-control" value="<?php echo $usun_prog_nr; ?>">
							</label>
							<span class="help-block"><?php echo $usun_prog_nr_err; ?></span>
						</div>
						
						<input type="submit" class="myButton" name="usun_butt" value="Zatwierdź">
						
					
				</form>	

				<?php
						if(!empty($usun_prog_nr))
						{
						    if(isset($_SESSION["validatedFlag"])&&$_SESSION["validatedFlag"]===true)
						    {
						        unset($_SESSION["validatedFlag"]);
						        unset($_SESSION["val_kind"]);
						        deleteSensor($usun_prog_nr);
						    }
                            elseif($_SERVER["REQUEST_METHOD"] == "POST")
                            {
                                $_SESSION["POST"] = $_POST;
                                $_SESSION["val_kind"]="delSensor";
                                Header("Location: validate.php");
                                exit();
                            }
						}
				?>
				
			</div>
			
			<br>
			
			<?php include "modules/tabelaCzujnikow.php" ?>
				
	</div>
	

</body>
</html>