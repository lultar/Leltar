<?php

include('db.php');

$selectedBuilding = $_GET['building'];
$selectedAisle = $_GET['aisle'];
$searchTerm = $_GET['search'];

$query = "SELECT Items.ItemID, Items.ItemName, Items.Description, Items.Quantity, Shelves.ShelfName, Aisles.AisleName, Buildings.BuildingName, MeasurementTypes.MeasurementType
          FROM Items 
          INNER JOIN Shelves ON Items.ShelfID = Shelves.ShelfID 
          INNER JOIN Aisles ON Shelves.AisleID = Aisles.AisleID 
          INNER JOIN Buildings ON Aisles.BuildingID = Buildings.BuildingID
          INNER JOIN MeasurementTypes ON Items.MeasurementTypeID = MeasurementTypes.MeasurementTypeID 
          WHERE 1=1";

$params = [];
if (!empty($selectedBuilding)) {
    $query .= " AND Buildings.BuildingName = ?";
    $params[] = $selectedBuilding;
}

if (!empty($selectedAisle)) {
    $query .= " AND Aisles.AisleName = ?";
    $params[] = $selectedAisle;
}

if (!empty($searchTerm)) {
    $query .= " AND Items.ItemName LIKE ?";
    $params[] = '%' . $searchTerm . '%';
}

$stmt = mysqli_prepare($connection, $query);
if ($stmt) {
    if (!empty($params)) {
        $types = str_repeat('s', count($params));
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='search-item'>";
            echo "<div>";
            echo "<strong>Item Name:</strong> " . $row['ItemName'] . "<br>";
            echo "<strong>Description:</strong> " . $row['Description'] . "<br>";
            echo "<strong>Quantity:</strong> " . $row['Quantity'] . " " . $row['MeasurementType'] . "<br>";
            echo "<strong>Shelf:</strong> " . $row['ShelfName'] . "<br>";
            echo "<strong>Aisle:</strong> " . $row['AisleName'] . "<br>";
            echo "<strong>Building:</strong> " . $row['BuildingName'] . "<br>";
            echo "</div>";
            echo "<button class='edit-button btn btn-primary' data-item-id='" . $row['ItemID'] . "' data-item-name='" . $row['ItemName'] . "' data-item-quantity='" . $row['Quantity'] . "' data-item-shelf='" . $row['ShelfName'] . "'>Edit</button>";
            echo "</div>";
        }
    } else {
        echo "No results found.";
    }

    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($connection);
}

mysqli_close($connection);
?>
