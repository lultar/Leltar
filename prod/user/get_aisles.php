<?php
// Include database connection
include('db.php');

// Get selected building from request
$selectedBuilding = $_GET['building'];

// Fetch distinct aisle names for the selected building
$query = "SELECT DISTINCT Aisles.AisleName FROM Aisles 
          JOIN Buildings ON Aisles.BuildingID = Buildings.BuildingID 
          WHERE Buildings.BuildingName = ?";
$stmt = mysqli_prepare($connection, $query);
mysqli_stmt_bind_param($stmt, 's', $selectedBuilding);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Populate aisle dropdown options
echo "<option value=''>Select Aisle</option>";
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . $row['AisleName'] . "'>" . $row['AisleName'] . "</option>";
    }
}

mysqli_stmt_close($stmt);
mysqli_close($connection);
?>
