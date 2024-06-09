<?php
// Include database connection
include('conn.php');

// Get selected building, aisle, and search term from request
$selectedBuilding = $_GET['building'];
$selectedAisle = $_GET['aisle'];
$searchTerm = $_GET['search'];

// Construct SQL query based on selected building, aisle, and search term
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

// Perform search query
$result = mysqli_query($conn, $query);

// Display search results
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
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
mysqli_close($conn);
?>