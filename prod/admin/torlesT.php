<?php
    require 'db.php';

    $item_id = $_POST['item_id'];

    $sql = "DELETE FROM Items WHERE ItemID=$item_id";

    if (mysqli_query($connection, $sql)) {
        header('Location: admin.php');
    } else {
        echo 'Error: ' . mysqli_error($connection);
    }

    mysqli_close($connection);
?>
