<?php
// Include config file
require_once "config_db.php";

// Initializing username variable and error message variable
$username = "";
$username_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = :username";

        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "Nazwa użytkownika zajęta";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Coś poszło nie tak. Spróbuj ponownie później.";
            }

            // Close statement
            unset($stmt);
        }
    }


    // Check input errors before inserting in database
    if(empty($username_err)){

        //create password -- todo: random password + send via mail ??? Other method ?
        //preset test password
        $password = trim("test1");


        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";

        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT);;

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                echo "Dodano użytkownika";
            }
            else
            {
                echo "Coś poszło nie tak. Spróbuj ponownie później";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Close connection
    unset($pdo);
}
?>

<!--todo: Move to part of administrator menu-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dodawanie nowego użytkownika</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Dodaj użytkownika</h2>
        <p>Wpisz nazwę nowego użytkownika, aby utworzyć dla niego konto</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Nazwa
                    <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                </label>
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Stwórz">
            </div>
        </form>
    </div>
</body>
</html>