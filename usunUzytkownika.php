<?php
function deleteUser($username)
{


    try
    {
        require "config_db.php";

        echo 'Connected to database<br>';

        /** @var $dbLink PDO */
        $sql = "SELECT id, username FROM users WHERE username = :username";

        if ($stmt = $dbLink->prepare($sql))
        {
            $dbLink->beginTransaction();

            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            if ($stmt->execute())
            {

                // Check if username exists, if yes then verify password
                if ($stmt->rowCount() == 1)
                {

                    $dbLink->exec("SET SQL_SAFE_UPDATES=0");

                    $qry = $dbLink->prepare("DELETE FROM users WHERE username=:paramUser");

                    $qry->bindParam(':paramUser', $username, PDO::PARAM_INT);

                    $qry->execute();

                    /*** closing connection ***/
                    $dbLink->exec("SET SQL_SAFE_UPDATES=1");
                    $dbLink->commit();
                    unset($dbLink);
                    echo "Usunięto użytkownika.<br>";
                    return true;
                } else
                {
                    echo "Brak użytkownika o podanej nazwie";
                    $dbLink->rollBack();
                    unset($dbLink);
                    return false;
                }
            }
        }
    } catch (PDOException $e)
    {
        $dbLink->rollBack();
        echo $e->getMessage();
        unset($dbLink);
        return false;

    }
}
