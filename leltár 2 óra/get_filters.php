<?php
// Include database connection
include('conn.php');

// Fetch distinct building names
$query = "SELECT DISTINCT BuildingName FROM Buildings";
$result = mysqli_query($conn, $query);

// Populate building dropdown options
echo "<optgroup label='Building'>";
if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . $row['BuildingName'] . "'>" . $row['BuildingName'] . "</option>";
    }
}
echo "</optgroup>";

// Fetch distinct aisle names
$query = "SELECT DISTINCT AisleName FROM Aisles";
$result = mysqli_query($conn, $query);

// Populate aisle dropdown options
echo "<optgroup label='Aisle'>";
if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . $row['AisleName'] . "'>" . $row['AisleName'] . "</option>";
    }
}
echo "</optgroup>";

// Close database connection
mysqli_close($connection);
?>
