<?php
 
include('db.php');

 
$selectedBuilding = isset($_GET['building']) ? $_GET['building'] : '';

if (!empty($selectedBuilding)) {
     
    $query = "SELECT DISTINCT Aisles.AisleName FROM Aisles 
              JOIN Buildings ON Aisles.BuildingID = Buildings.BuildingID 
              WHERE Buildings.BuildingName = ?";
    $stmt = mysqli_prepare($connection, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $selectedBuilding);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
             
            echo "<option value=''>Select Aisle</option>";
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . htmlspecialchars($row['AisleName'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['AisleName'], ENT_QUOTES, 'UTF-8') . "</option>";
                }
            }
        } else {
             
            echo "<option value=''>Error loading aisles</option>";
        }

        mysqli_stmt_close($stmt);
    } else {
         
        echo "<option value=''>Error preparing query</option>";
    }
} else {
     
    echo "<option value=''>Invalid building selection</option>";
}

mysqli_close($connection);
?>
