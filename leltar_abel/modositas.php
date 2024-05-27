<?php
    require "db.php";

    if (isset($_POST['user_id']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['user_type'])) {
        $userID = intval($_POST['user_id']);
        $username = $_POST['username'];
        $password = $_POST['password'];
        $userType = $_POST['user_type'];

        $stmt = $connection->prepare("UPDATE Users SET Username = ?, Password = ?, UserType = ? WHERE UserID = ?");
        $stmt->bind_param("sssi", $username, $password, $userType, $userID);

        if ($stmt->execute()) {
            echo "User updated successfully.";
        } else {
            echo "Error updating user: " . $connection->error;
        }

        $stmt->close();
    } else {
        echo "Invalid request.";
    }

    $connection->close();

    header("Location: index.php");
    exit();
?>
