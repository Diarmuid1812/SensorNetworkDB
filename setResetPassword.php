<?php
/**
 * Script to set password first time a user logs in
 * or reset it after login.
 */

// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: usrLogin.php");
    exit;
}
try
{
// Include config file
    require_once "config_db.php";

// Define variables and initialize with empty values
    $new_password = $confirm_password = "";
    $new_password_err = $confirm_password_err = "";
// Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // Validate new password
        if (empty(trim($_POST["new_password"])))
        {
            $new_password_err = "Pole nie może być puste.";
        } elseif (strlen(trim($_POST["new_password"])) < 5)
        {
            $new_password_err = "Hasło musi mieć co najmniej 5 znaków.";
        }
        else
        {
            $new_password = trim($_POST["new_password"]);
            $new_password = filter_var($new_password,FILTER_VALIDATE_REGEXP,
                array("options"=>array("regexp"=>'/^[^\t\r\n\\\'\\"\\<\\>\\\]{5,}$/')));
            if($new_password === false)
            {
                $new_password_err = "Wykryto znaki białe inne niż spacja lub zastrzeżone znaki w haśle";
            }
        }

        // Validate confirm password
        if (empty(trim($_POST["confirm_password"])))
        {
            $confirm_password_err = "Wprowadź ponownie hasło.";
        }
        else
        {
            $confirm_password = trim($_POST["confirm_password"]);
            $confirm_password = filter_var($confirm_password,FILTER_VALIDATE_REGEXP,
                array("options"=>array("regexp"=>'/^[^\t\r\n\\\'\\"\\<\\>\\\]{5,}$/')));
            if($confirm_password === false)
            {
                $confirm_password = "Wykryto znaki białe inne niż spacja lub zastrzeżone znaki w haśle";
            }
            elseif (empty($new_password_err) && ($new_password != $confirm_password))
            {
                $confirm_password_err = "Hasła nie są identyczne!";
            }
        }

        // Check input errors before updating the database
        if (empty($new_password_err) && empty($confirm_password_err))
        {

            $dbLink->beginTransaction();

            // Prepare an update statement
            $sql = "UPDATE users SET password = :password WHERE id = :id";

            if ($stmt = $dbLink->prepare($sql))
            {
                // Bind variables to the prepared statement as parameters
                $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
                $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);

                // Set parameters
                $param_password = password_hash($new_password, PASSWORD_DEFAULT);
                $param_id = $_SESSION["id"];

                // Attempt to execute the prepared statement
                if ($stmt->execute())
                {
                    $passwChangedUpdate= $dbLink->prepare("UPDATE users SET passw_changed = true 
                                                        WHERE username = :username");
                    $passwChangedUpdate->bindParam(':username',$_SESSION["username"],PDO::PARAM_STR);
                    if(!$passwChangedUpdate->execute())
                    {
                        $dbLink->rollBack();
                        echo "Coś poszło nie tak. Spróbuj ponownie później";
                    }
                    else
                    {
                        // Password updated successfully. Destroy the session, and redirect to login page
                        $dbLink->commit();
                        session_destroy();
                        unset($passwChangedUpdate);
                        unset($stmt);
                        unset($dbLink);
                        header("location: usrLogin.php");
                        exit();
                    }
                } else
                {
                    $dbLink->rollBack();
                    echo "Coś poszło nie tak. Spróbuj ponownie później";
                }

                // Close statement
                unset($stmt);
            }
        }

        // Close connection
        unset($dbLink);
    }
}
catch (PDOException $e)
{
    $dbLink->rollBack();
    echo "Błąd:" . $e->getMessage();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="logowaniestyl2.css">
    <title>Reset Password</title>
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style> -->
</head>
<body>
<div class="wrapper">
    <h2 class="header">Zresetuj hasło</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	<div class="foo">
		<p class="pogrub">Wprowadź i potwierdź nowe hasło</p>
        <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>">
			<div class="odsun">
            <label>Nowe hasło
            <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>">
            <span class="ero"><?php echo $new_password_err; ?></span>
            </label>
			</div>
        </div>
        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
			<div class="odsun">
            <label>Powtórz hasło
            <input type="password" name="confirm_password" class="form-control">
            <span class="ero"><?php echo $confirm_password_err; ?></span>
            </label>
			</div>
        </div>
	
		<div class="przycisk3">
			<div class="form-group">
				<input type="submit" class="myButton" value="Zmień">
				<a class="myButton2" href="interfejsGlowny.phtml">Powrót</a>
			</div>
		</div>
	</div>
    </form>
</div>
</body>
</html>
