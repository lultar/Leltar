<?php
    require 'db.php';

    $item_id = $_POST['item_id'];
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $quantity = $_POST['quantity'];
    $real_quantity = $_POST['real_quantity'];
    $measurement_type = $_POST['measurement_type'];
    $shelf_name = $_POST['shelf_name'];

    $measurement_type_query = "SELECT MeasurementTypeID FROM MeasurementTypes WHERE MeasurementType = '$measurement_type'";
    $result = mysqli_query($connection, $measurement_type_query);
    $row = mysqli_fetch_assoc($result);
    $measurement_type_id = $row['MeasurementTypeID'];

    $shelf_query = "SELECT ShelfID FROM Shelves WHERE ShelfName = '$shelf_name'";
    $result = mysqli_query($connection, $shelf_query);
    $row = mysqli_fetch_assoc($result);
    $shelf_id = $row['ShelfID'];

    $sql = "UPDATE Items SET ItemName='$item_name', Description='$description', Quantity=$quantity, RealQuantity=$real_quantity, MeasurementTypeID=$measurement_type_id, ShelfID=$shelf_id WHERE ItemID=$item_id";

    if (mysqli_query($connection, $sql)) {
        header('Location: admin.php');
    } else {
        echo 'Error: ' . mysqli_error($connection);
    }

    mysqli_close($connection);
?>
