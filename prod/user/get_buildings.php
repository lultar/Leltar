<?php
// Include database connection
include('db.php');

// Fetch distinct building names
$query = "SELECT DISTINCT BuildingName FROM Buildings";
$result = mysqli_query($connection, $query);

if ($result) {
    // Populate building dropdown options
    echo "<option value=''>Select Building</option>";
    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            echo "<option value='" . htmlspecialchars($row['BuildingName'], ENT_QUOTES, 'UTF-8') . "'>" . htmlspecialchars($row['BuildingName'], ENT_QUOTES, 'UTF-8') . "</option>";
        }
    }
} else {
    // Handle query failure
    echo "<option value=''>Error loading buildings</option>";
}

mysqli_close($connection);
?>
