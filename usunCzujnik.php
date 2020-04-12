<?php
function deleteSensor($num_id)
{

    $link = mysqli_connect("localhost", "root", "", "czujniki");

// Check connection
    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    mysqli_query($link, "SET SQL_SAFE_UPDATES = 0;");

// Attempt delete query execution
    $sql = "DELETE FROM czujnik WHERE id=".$num_id;
    if (mysqli_query($link, $sql)) {
        echo "Records were deleted successfully.";
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }

    mysqli_query($link, "SET SQL_SAFE_UPDATES = 1;");

// Close connection
    mysqli_close($link);
}