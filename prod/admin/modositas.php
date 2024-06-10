<?php
    require "db.php";

    if (isset($_POST['user_id']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['user_type'])) {
        $userID = intval($_POST['user_id']);
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT); 
        $userType = $_POST['user_type'];

        $stmt = $connection->prepare("UPDATE Users SET Username = ?, Password = ?, UserType = ? WHERE UserID = ?");
        if (!$stmt) {
            die("Prepare failed: (" . $connection->errno . ") " . $connection->error);
        }

        $stmt->bind_param("sssi", $username, $password, $userType, $userID);

        if ($stmt->execute()) {
            header("Location: admin.php?success=Sikeresen módosítva");
            exit();
        } else {
            $error_message = $stmt->error;
            header("Location: admin.php?error=Nem sikerült módosítani az adatokat: " . urlencode($error_message));
            exit();
        }

        $stmt->close();
    } else {
        echo "Invalid request.";
    }

    $connection->close();
?>
