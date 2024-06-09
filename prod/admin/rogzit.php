<?php
    require "db.php";

    $response = array();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = mysqli_real_escape_string($connection, $_POST['username']);
        $password = mysqli_real_escape_string($connection, $_POST['password']);
        $user_type = mysqli_real_escape_string($connection, $_POST['user_type']);

        $sql = "INSERT INTO Users (Username, Password, UserType) VALUES ('$username', '$password', '$user_type')";

        if (mysqli_query($connection, $sql)) {
            $response['status'] = 'success';
        } else {
            $response['status'] = 'error';
            $response['message'] = "Error: " . $sql . "<br>" . mysqli_error($connection);
        }
    }

    mysqli_close($connection);

    echo json_encode($response);
?>
