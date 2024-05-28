<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bejelentkezés</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="login-container">
        <h2>Bejelentkezés</h2>
        <form action="login.php" method="post">
            <label for="username">Felhasználónév:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Jelszó:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Bejelentkezés</button>
        </form>
    </div>
</body>
</html>

<?php
    session_start();
    require "db.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT UserID, Username, UserType FROM Users WHERE Username=? AND Password=?";
        $stmt = mysqli_prepare($connection, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        mysqli_stmt_bind_result($stmt, $userID, $username, $userType);

        if (mysqli_stmt_num_rows($stmt) == 1) {
            mysqli_stmt_fetch($stmt);
            $_SESSION['UserID'] = $userID;
            $_SESSION['Username'] = $username;
            $_SESSION['UserType'] = $userType;

            if ($userType == 1) {
                header("Location: admin.php");
            } elseif ($userType == 2) {
                header("Location: user.php");
            } else {
                echo "Érvénytelen felhasználói típus.";
            }
        } else {
            echo "<script>alert('Hibás felhasználónév vagy jelszó.');</script>";
        }
        mysqli_stmt_close($stmt);
    }
    mysqli_close($connection);
?>
