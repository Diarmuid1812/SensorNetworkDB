<?php
/**
 * Script to set password first time a user logs in
 * or reset it after login.
 */


//$param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

// Initialize the session
session_start();
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: usrLogin.php");
    exit;
}

// Include config file
require_once "config_db.php";

// Define variables and initialize with empty values
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Pole nie może być puste.";
    }
    elseif(strlen(trim($_POST["new_password"])) < 6)
    {
        $new_password_err = "Hasło musi mieć co najmniej 6 znaków.";
    }
    else
    {
        $new_password = trim($_POST["new_password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"])))
    {
        $confirm_password_err = "Wprowadź ponownie hasło.";
    }
    else
    {
        $confirm_password = trim($_POST["confirm_password"]);
        if (empty($new_password_err) && ($new_password != $confirm_password))
        {
            $confirm_password_err = "Hasła nie są identyczne!";
        }
    }

    // Check input errors before updating the database
    if(empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE users SET password = :password WHERE id = :id";

        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);

            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: usrLogin.php");
                exit();
            } else{
                echo "Coś poszło nie tak. Spróbuj ponownie później";
            }

            // Close statement
            unset($stmt);
        }
    }

    // Close connection
    unset($pdo);
}