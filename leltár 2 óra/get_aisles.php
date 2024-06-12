<?php
 
include('conn.php');

 
$selectedBuilding = $_GET['building'];

 
$query = "SELECT DISTINCT Aisles.AisleName FROM Aisles JOIN Buildings ON Aisles.BuildingID = Buildings.BuildingID WHERE Buildings.BuildingName = '$selectedBuilding'";
$result = mysqli_query($conn, $query);

 
echo "<option value=''>Select Aisle</option>";
if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . $row['AisleName'] . "'>" . $row['AisleName'] . "</option>";
    }
}
?>
