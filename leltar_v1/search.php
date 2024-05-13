<?php
// Include database connection
include('db.php');

// Get selected building and aisle from request
$selectedBuilding = $_GET['building'];
$selectedAisle = $_GET['aisle'];

// Construct SQL query based on selected building and aisle
if (!empty($selectedBuilding) && !empty($selectedAisle)) {
    $query = "SELECT * FROM Items 
              WHERE ShelfID IN (SELECT ShelfID FROM Shelves WHERE AisleID IN (SELECT AisleID FROM Aisles WHERE BuildingID = (SELECT BuildingID FROM Buildings WHERE BuildingName = '$selectedBuilding') AND AisleName = '$selectedAisle'))";
} elseif (!empty($selectedBuilding)) {
    $query = "SELECT * FROM Items 
              WHERE ShelfID IN (SELECT ShelfID FROM Shelves WHERE AisleID IN (SELECT AisleID FROM Aisles WHERE BuildingID = (SELECT BuildingID FROM Buildings WHERE BuildingName = '$selectedBuilding')))";
} else {
    $query = "SELECT * FROM Items";
}

// Perform search query
$result = mysqli_query($connection, $query);

// Display search results
if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo "Item ID: " . $row['ItemID'] . "<br>";
        echo "Item Name: " . $row['ItemName'] . "<br>";
        echo "Description: " . $row['Description'] . "<br>";
        // Add other fields as needed
        echo "<hr>";
    }
} else {
    echo "No results found.";
}

// Close database connection
mysqli_close($connection);
?>
