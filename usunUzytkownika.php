<?php
function deleteUser($username)
{
    $success = false;

    try
    {
        require "config_db.php";

        /** @var $dbLink PDO */
        $sql = "SELECT id, username FROM users WHERE username = :username";

        if ($stmt = $dbLink->prepare($sql))
        {
            $dbLink->beginTransaction();

            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":username", $username, PDO::PARAM_STR);
            if ($stmt->execute())
            {

                // Check if username exists
                if ($stmt->rowCount() == 1)
                {

                    $dbLink->exec("SET SQL_SAFE_UPDATES=0");

                    $qry = $dbLink->prepare("DELETE FROM users WHERE username=:paramUser");

                    $qry->bindParam(':paramUser', $username, PDO::PARAM_STR);

                    $qry->execute();

                    /*** closing connection ***/
                    $dbLink->exec("SET SQL_SAFE_UPDATES=1");
                    $dbLink->commit();
                    unset($dbLink);
                    echo "Usunięto użytkownika $username. <br>";
                    $success = true;
                } else
                {
                    echo "Brak użytkownika o podanej nazwie<br>";
                    $dbLink->rollBack();
                    unset($dbLink);
                }
            }
        }
    } catch (PDOException $e)
    {
        $dbLink->rollBack();
        echo $e->getMessage()."<br>";
        unset($dbLink);

    }
    finally
    {
        return $success;
    }
}
