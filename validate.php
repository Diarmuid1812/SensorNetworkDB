<?php

// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] !== true){
    header("location: interfejsGlowny.phtml");
    exit;
}

// Include config file
require_once "config_db.php";

// Define variables and initialize with empty values
$password = "";
$password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Nie podano hasła.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT password FROM users WHERE username = :username";

        if($stmt = $dbLink->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

            // Set parameters
            $param_username = trim($_SESSION["username"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if username exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $hashed_password = $row["password"];
                        if(password_verify($password, $hashed_password)){

                            //validated and confirmed
                            $_SESSION["validatedFlag"] = true;
                            //previous page
                            header("location: ". $_SERVER['HTTP_REFERER']);
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "Niepoprawne hasło";
                        }
                    }
                }
            } else{
                echo "Coś poszło nie tak. Spróbuj ponownie później";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Close connection
    unset($dbLink);
}

/*<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
	*/

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="logowaniestyl2.css">
    
</head>
<body>
<div class="wrapper">
    <h2 class="header">Potwierdź zmianę hasłem</h2>
	<div class="foo">

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
				<label>Hasło
				<input type="password" name="password" class="form-control">
				<span class="help-block"><?php echo $password_err; ?></span>
				</label>
			</div>
			<br>
			<div class="form-group">
				<input type="submit" class="myButton" value="Potwierdź">
				<a class="myButton2" href="javascript:history.go(-1)">Powrót</a>
			</div>
		</form>
	</div>
</div>
</body>
</html>
