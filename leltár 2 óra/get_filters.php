<?php
 
include('conn.php');

 
$query = "SELECT DISTINCT BuildingName FROM Buildings";
$result = mysqli_query($conn, $query);

 
echo "<optgroup label='Building'>";
if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . $row['BuildingName'] . "'>" . $row['BuildingName'] . "</option>";
    }
}
echo "</optgroup>";

 
$query = "SELECT DISTINCT AisleName FROM Aisles";
$result = mysqli_query($conn, $query);

 
echo "<optgroup label='Aisle'>";
if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . $row['AisleName'] . "'>" . $row['AisleName'] . "</option>";
    }
}
echo "</optgroup>";

 
mysqli_close($connection);
?>
