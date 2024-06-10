<?php
    require "db.php";

    $orderColumn = isset($_GET['orderColumn']) ? $_GET['orderColumn'] : 'Users.UserID';
    $orderDir = isset($_GET['orderDir']) ? $_GET['orderDir'] : 'ASC';

    $sql = "SELECT Users.UserID, Users.Username, Users.Password, UserTypes.UserTypeID, UserTypes.UserTypeName FROM Users INNER JOIN UserTypes ON Users.UserType = UserTypes.UserTypeID ORDER BY $orderColumn $orderDir";
    $result = mysqli_query($connection, $sql);

    $userData = [];

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $userData[] = $row;
        }
    }

    echo json_encode($userData);
    mysqli_close($connection);
?>
