<?php
    require "db.php";

    if (isset($_POST['user_id'])) {
        $userID = intval($_POST['user_id']);

        

        $stmt->close();
    } else {
        echo "Invalid request.";
    }

    $connection->close();

    header("Location: admin.php");
    exit();
?>