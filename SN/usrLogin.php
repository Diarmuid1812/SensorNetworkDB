<?php

// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: interfejsGlowny.phtml");
    exit;
}

// Include config file
require "modules/config_db.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Podaj nazwę użytkownika.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Nie podano hasła.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password, admin, passw_changed FROM users WHERE username = :username";

        if($stmt = $dbLink->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if username exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["id"];
                        $username = $row["username"];
                        $hashed_password = $row["password"];
                        $isPasswChanged = $row["passw_changed"];
                        $isAdmin = $row["admin"];
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["isPasswChanged"] = boolval($isPasswChanged);
                            $_SESSION["permission"] = boolval($isAdmin);
                            // Redirect user to welcome page
                            header("location: interfejsGlowny.phtml");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "Niepoprawne hasło";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "Brak użytkownika o podanej nazwie";
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
	//https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="CSS/logowaniestyl.css">
    
</head>
<body>
<div class="wrapper">
    <div class = "header"><h2>Logowanie</h2></div>
	
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
		<div class = "foo">
			<div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
				<div class = "odsun">
					<label>Nazwa użytkownika
					<input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                    </label>
					<div class = "ero"><span class="help-block"><?php echo $username_err; ?></span></div>

				</div>	
			</div>
			<div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
				<div class = "odsun">
					<label>Hasło
					<input type="password" name="password" class="form-control">
                    </label>
                    <div class = "ero"><span class="help-block"><?php echo $password_err; ?></span></div>
				</div>
			</div>
			<div class = "przycisk">
				<div class="form-group">
					<input type="submit" class="myButton" value="Zaloguj się">
				</div>
			</div>
		</div>
    </form>
	
</div>
</body>
</html>
