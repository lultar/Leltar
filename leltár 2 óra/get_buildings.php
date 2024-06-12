<?php
 
include('conn.php');

 
$query = "SELECT DISTINCT BuildingName FROM Buildings";
$result = mysqli_query($conn, $query);

 
echo "<option value=''>Select Building</option>";
if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . $row['BuildingName'] . "'>" . $row['BuildingName'] . "</option>";
    }
}
?>
