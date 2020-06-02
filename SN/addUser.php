<?php

session_start();
// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: usrLogin.php");
    exit;
} elseif (!isset($_SESSION["permission"])||$_SESSION["permission"]!==true)
{
    header("location: interfejsGlowny.php");
    exit;
}


// Include config file
require 'modules/mailFunc.php';
require 'modules/usunUzytkownika.php';

// Initializing username variable and error message variable
$username = "";
$del_username = "";
$email = "";
$username_err = $del_username_err = $email_err = "";
$delUsr_message = "";
$addUsr_message = "";

//handle deletion
if(isset($_SESSION["POST"])) //if validated
{
    $temp = $_SESSION["POST"];
    unset($_SESSION["POST"]);
    $del_username = $temp["del_username"];
}

try
{
    //database connection
    require_once "modules/config_db.php";
    $dbLink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST" )
    {
        if (isset($_POST["username"]) && isset($_POST["email"]))
        {
            $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);

            // Validate username
            if (empty(trim($_POST["username"])))
            {
                $username_err = "Podaj nazwę użytkownika.";
            } elseif (empty(trim($_POST["email"])))
            {
                $email_err = "Podaj email nowego użytkownika.";
            } elseif ($email === false)
            {
                $email_err = "Niepoprawny email";
            } else
            {
                // Prepare a select statement
                $sql = "SELECT id FROM users WHERE username = :username";

                if ($stmt = $dbLink->prepare($sql))
                {
                    // Bind variables to the prepared statement as parameters
                    $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

                    // Set parameters
                    $param_username = trim($_POST["username"]);

                    // Attempt to execute the prepared statement
                    if ($stmt->execute())
                    {
                        if ($stmt->rowCount() == 1)
                        {
                            $username_err = "Nazwa użytkownika zajęta";
                        } else
                        {
                            $username = filter_var(trim($_POST["username"]), FILTER_VALIDATE_REGEXP,
                                array("options" => array("regexp" => '/^[A-Za-z0-9ąćęłńóśżźĄĆĘŁŃÓŚŻŹ_!\\.\\-\\$]{4,30}$/')));
                            if ($username === false)
                            {
                                $username_err = "Niepoprawna nazwa użytkownika <br>
                                             Dozwolone znaki to A-Z, a-z, 0-9, <br>
                                             'ąćęłńóśżźĄĆĘŁŃÓŚŻŹ', '-', '_', '.', '!', '$'";
                            }
                        }
                    } else
                    {
                        $addUsr_message = "Coś poszło nie tak. Spróbuj ponownie później.";
                    }


                    // Close statement
                    unset($stmt);
                }

                $sql = "SELECT id FROM users WHERE email = :email";

                if ($stmt = $dbLink->prepare($sql))
                {
                    // Bind variables to the prepared statement as parameters
                    $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);

                    // Set parameters
                    $param_email = $email;

                    // Attempt to execute the prepared statement
                    if ($stmt->execute())
                    {
                        if ($stmt->rowCount() == 1)
                        {
                            $email_err = "Istnieje już konto dla tego adresu";
                            $email = "";
                        }
                    } else
                    {
                        $addUsr_message = "Coś poszło nie tak. Spróbuj ponownie później.";
                    }

                    // Close statement
                    unset($stmt);
                }
            }


            // Check input errors before inserting in database
            if (empty($username_err) && empty($email_err))
            {

                //create password
                $password = trim(bin2hex(random_bytes(5)));
                mailTo($email, 'Temporary password', $password);
                $dbLink->beginTransaction();

                //Checking if new user should be granted administrator privillages
                $isAdmin = (isset($_POST["isAdmin"]) && $_POST["isAdmin"] === "true");

                // Prepare an insert statement
                $sql = "INSERT INTO users (username, email, password, admin) VALUES (:username, :email, :password, :isAdmin)";

                if ($stmt = $dbLink->prepare($sql))
                {
                    // Bind variables to the prepared statement as parameters
                    $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
                    $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
                    $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
                    $stmt->bindParam(":isAdmin", $isAdmin, PDO::PARAM_BOOL);

                    // Set parameters
                    $param_username = $username;
                    $param_email = $email;
                    $param_password = password_hash($password, PASSWORD_DEFAULT);

                    // Attempt to execute the prepared statement
                    if ($stmt->execute())
                    {
                        $dbLink->commit();
                        $addUsr_message = "Dodano użytkownika";
                    } else
                    {
                        $dbLink->rollBack();
                        $addUsr_message = "Coś poszło nie tak. Spróbuj ponownie później";
                    }

                    // Close statement
                    unset($stmt);
                }
            }

            // Close connection
            unset($dbLink);
        }
        elseif (isset($_POST["del_username"]))
        {
            $sql = "SELECT id FROM users WHERE username = :delUsrParam";

                if ($stmt = $dbLink->prepare($sql))
                {
                    // Bind variables to the prepared statement as parameters
                    $stmt->bindParam(":delUsrParam", $delUsrParam, PDO::PARAM_STR);

                    // Set parameters
                    $delUsrParam = trim($_POST["del_username"]);

                    // Attempt to execute the prepared statement
                    if ($stmt->execute())
                    {
                        if ($stmt->rowCount() < 1)
                        {
                            $del_username_err= "Brak użytkownika o pddanej nazwie";
                        }
                        else
                            $del_username = $_POST["del_username"]; //success
                    } else
                    {
                        $delUsr_message = "Coś poszło nie tak. Spróbuj ponownie później.";
                    }

                    // Close statement
                    unset($stmt);
                }

        }
    }
}
catch (PDOException $E)
{
    $dbLink->rollBack();
    echo $E->getMessage();
}
catch (Exception $e)
{
    echo $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dodawanie nowego użytkownika</title>
    <link rel="stylesheet" href="CSS/teststyl.css">
    
</head>
<body>
    
        <div class="header"><h1>Dodaj użytkownika</h1></div>
		
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
			
			<!-- dodawanie uzytkownikow -->
				<p>Wpisz nazwę nowego użytkownika, aby utworzyć dla niego konto</p>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				
					<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
						<label>Nazwa
							<input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
						</label>
						<div class="ero"><span class="help-block"><?php echo $username_err; ?></span></div>
					</div>
					
					<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
						<label>E-mail
							<input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
						</label>
						<div class="ero"><span class="help-block"><?php echo $email_err; ?></span></div>
					</div>
					
					<br>
					
					<div class="form-group">
						<input type="checkbox" id="isAdmin" name="isAdmin" value="true">
						<label for="isAdmin">Nadaj użytkownikowi uprawnienia administratora</label><br>
					</div>

                    <p><?php echo $addUsr_message;?></p>
					<div class="form-group">
						<input type="submit" class="myButton" value="Stwórz">
					</div>
				
				</form>
				
				
				<!-- Usuwanie uzytkownikow -->
				
				<p>Wpisz nazwę użytkownika, którego chcesz usunąć</p>
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				
					<div class="form-group <?php echo (!empty($del_username_err)) ? 'has-error' : ''; ?>">
						<label>Nazwa
							<input type="text" name="del_username" class="form-control" value="<?php echo $del_username; ?>">
						</label>
						<div class="ero"><span class="help-block"><?php echo $del_username_err; ?></span></div>
					</div>
                    <p><?php echo $delUsr_message;?></p>
					<div class="form-group">
						<input type="submit" class="myButton" value="Usuń">
					</div>
				
				</form>
				
				<?php
						if(!empty($del_username))
						{
						    if(isset($_SESSION["validatedFlag"])&&$_SESSION["validatedFlag"]===true)
						    {
						        unset($_SESSION["validatedFlag"]);
						        unset($_SESSION["val_kind"]);
						        deleteUser($del_username);
						    }
                            elseif($_SERVER["REQUEST_METHOD"] == "POST")
                            {
                                $_SESSION["POST"] = $_POST;
                                $_SESSION["val_kind"]="delUser";
                                Header("Location: validate.php");
                                exit();
                            }
						}
				?>
				
				
				<?php include "modules/tabelaUzytkownikow.php" ?>
				
			</div>
		</div>
</body>
</html>