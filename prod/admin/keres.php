<?php
    session_start();
    require "db.php";

    if (!isset($_SESSION['UserID']) || $_SESSION['UserType'] != 1) {
        header("Location: /Leltar/prod/index.php");
        exit();
    }

    $field = isset($_GET['field']) ? $_GET['field'] : '';
    $query = isset($_GET['query']) ? $_GET['query'] : '';

    $allowedFields = ['UserID', 'Username', 'Password', 'UserType'];
    if (!in_array($field, $allowedFields)) {
        echo "Invalid field selected";
        exit();
    }

    $sql = "SELECT Users.UserID, Users.Username, Users.Password, UserTypes.UserTypeID, UserTypes.UserTypeName 
            FROM Users 
            INNER JOIN UserTypes ON Users.UserType = UserTypes.UserTypeID 
            WHERE UserTypeID = '2' AND $field LIKE '%$query%' 
            ORDER BY Users.UserID";

    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['UserID']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Username']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Password']) . "</td>";
            echo "<td>" . htmlspecialchars($row['UserTypeName']) . "</td>";
            echo '<td><button onclick="Modositas(' . $row['UserID'] . ', \'' . htmlspecialchars($row['Username']) . '\', \'' . htmlspecialchars($row['Password']) . '\', \'' . htmlspecialchars($row['UserTypeID']) . '\')" class="btn btn-primary">Módosítás</button></td>';
            echo '<td><button onclick="Torles(' . $row['UserID'] . ')" class="btn btn-danger">Törlés</button></td>';
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>Nincs találat.</td></tr>";
    }

    mysqli_close($connection);
?>
