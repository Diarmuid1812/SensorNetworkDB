<?php
// Include config file
require 'mailTest.php';


// Initializing username variable and error message variable
$username = "";
$email = "";
$username_err = $email_err = "";

try
{
    //database connection
    require_once "config_db.php";
    $dbLink->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Processing form data when form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST")
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
                        $username = filter_var(trim($_POST["username"]),FILTER_VALIDATE_REGEXP,
                            array("options"=>array("regexp"=>'/^[A-Za-z0-9ąćęłńóśżźĄĆĘŁŃÓŚŻŹ_!\\.\\-\\$]{4,30}$/')));
                        if($username === false)
                        {
                            $username_err = "Niepoprawna nazwa użytkownika <br>
                                             Dozwolone znaki to A-Z, a-z, 0-9, <br>
                                             'ąćęłńóśżźĄĆĘŁŃÓŚŻŹ', '-', '_', '.', '!', '$'";
                        }


                    }


                } else
                {
                    echo "Coś poszło nie tak. Spróbuj ponownie później.";
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
            mailTo($email,'Temporary password',$password);
            $dbLink->beginTransaction();

            //Checking if new user should be granted administrator privillages
            $isAdmin = (isset($_POST["isAdmin"])&&$_POST["isAdmin"]==="true");


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
                $param_email    = $email;
                $param_password = password_hash($password, PASSWORD_DEFAULT);

                // Attempt to execute the prepared statement
                if ($stmt->execute())
                {
                    $dbLink->commit();
                    echo "Dodano użytkownika";
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
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>E-mail
                    <input type="text" name="email" class="form-control" value="<?php echo $email; ?>">
                </label>
                <span class="help-block"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <input type="checkbox" id="isAdmin" name="isAdmin" value="true">
                <label for="isAdmin">Uprawnienia administratora</label><br>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Stwórz">
                <a class="btn btn-link" href="interfejsGlowny.phtml">Powrót</a>
            </div>
        </form>
    </div>
</body>
</html>