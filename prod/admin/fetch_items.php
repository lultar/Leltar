<?php
    require "db.php";

    $orderColumn = isset($_GET['orderColumn']) ? $_GET['orderColumn'] : 'Items.ItemID';
    $orderDir = isset($_GET['orderDir']) ? $_GET['orderDir'] : 'ASC';

    $sql = "SELECT Items.ItemID, Items.ItemName, Items.Description, Items.Quantity, Items.RealQuantity, MeasurementTypes.MeasurementType, Shelves.ShelfName FROM Items INNER JOIN MeasurementTypes ON Items.MeasurementTypeID = MeasurementTypes.MeasurementTypeID INNER JOIN Shelves ON Items.ShelfID = Shelves.ShelfID ORDER BY $orderColumn $orderDir";
    $result = mysqli_query($connection, $sql);

    $productData = [];

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $productData[] = $row;
        }
    }

    echo json_encode($productData);
    mysqli_close($connection);
?>
