<?php
// Include database connection
include('conn.php');

// Get selected building from request
$selectedBuilding = $_GET['building'];

// Fetch distinct aisle names for the selected building
$query = "SELECT DISTINCT Aisles.AisleName FROM Aisles JOIN Buildings ON Aisles.BuildingID = Buildings.BuildingID WHERE Buildings.BuildingName = '$selectedBuilding'";
$result = mysqli_query($conn, $query);

// Populate aisle dropdown options
echo "<option value=''>Select Aisle</option>";
if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . $row['AisleName'] . "'>" . $row['AisleName'] . "</option>";
    }
}
?>
