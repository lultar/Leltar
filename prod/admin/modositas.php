<?php
    require "db.php";

    if (isset($_POST['user_id']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['user_type'])) {
        $userID = intval($_POST['user_id']);
        $username = $_POST['username'];
        $password = $_POST['password'];
        $userType = $_POST['user_type'];

        $stmt_check = $connection->prepare("SELECT UserID FROM Users WHERE Username = ?");
        $stmt_check->bind_param("s", $username);
        $stmt_check->execute();
        $stmt_check->store_result();
        
        if ($stmt_check->num_rows > 0) {
            header("Location: admin/admin.php?error=Nem sikerült módosítani az adatokat");
            exit();
        }

        $stmt_check->close();

        $stmt = $connection->prepare("UPDATE Users SET Username = ?, Password = ?, UserType = ? WHERE UserID = ?");
        $stmt->bind_param("ssii", $username, $password, $userType, $userID);

        if ($stmt->execute()) {
            header("Location: admin/admin.php");
            exit();
        } else {
            header("Location: admin/admin.php?error=Nem sikerült módosítani az adatokat");
            exit();
        }

        $stmt->close();
    } else {
        echo "Invalid request.";
    }

    $connection->close();
?>
