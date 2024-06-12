<?php
 
include('conn.php');

 
$selectedBuilding = $_GET['building'];
$selectedAisle = $_GET['aisle'];
$searchTerm = $_GET['search'];

 
$query = "SELECT * FROM Items WHERE 1=1";

if (!empty($selectedBuilding)) {
    $query .= " AND ShelfID IN (SELECT ShelfID FROM Shelves WHERE AisleID IN 
               (SELECT AisleID FROM Aisles WHERE BuildingID = 
               (SELECT BuildingID FROM Buildings WHERE BuildingName = '$selectedBuilding')))";
}

if (!empty($selectedAisle)) {
    $query .= " AND ShelfID IN (SELECT ShelfID FROM Shelves WHERE AisleID IN 
               (SELECT AisleID FROM Aisles WHERE BuildingID = 
               (SELECT BuildingID FROM Buildings WHERE BuildingName = '$selectedBuilding') AND AisleName = '$selectedAisle'))";
}

if (!empty($searchTerm)) {
    $query .= " AND ItemName LIKE '%$searchTerm%'";
}

 
$result = mysqli_query($conn, $query);

 
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "Item ID: " . $row['ItemID'] . "<br>";
        echo "Item Name: " . $row['ItemName'] . "<br>";
        echo "Description: " . $row['Description'] . "<br>";
         
        echo "<hr>";
    }
} else {
    echo "No results found.";
}

 
mysqli_close($conn);
?>