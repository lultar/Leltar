<?php
    require "db.php";

    if (isset($_POST['user_id'])) {
        $userID = intval($_POST['user_id']);

        $stmt = $connection->prepare("DELETE FROM Users WHERE UserID = ?");
        $stmt->bind_param("i", $userID);

        if ($stmt->execute()) {
            echo "User deleted successfully.";
        } else {
            echo "Error deleting user: " . $connection->error;
        }

        $stmt->close();
    } else {
        echo "Invalid request.";
    }

    $connection->close();

    header("Location: admin.php");
    exit();
?>