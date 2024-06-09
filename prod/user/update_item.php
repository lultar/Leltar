<?php
// Include database connection
include('db.php');

// Get item details from POST request
$itemId = $_POST['itemId'];
$itemQuantity = $_POST['itemQuantity'];
$itemShelf = $_POST['itemShelf'];

// Update item in the database
$query = "UPDATE Items 
          SET Quantity = ?, 
              ShelfID = (SELECT ShelfID FROM Shelves WHERE ShelfName = ?)
          WHERE ItemID = ?";
$stmt = mysqli_prepare($connection, $query);

if ($stmt) {
    mysqli_stmt_bind_param($stmt, 'dsi', $itemQuantity, $itemShelf, $itemId);
    if (mysqli_stmt_execute($stmt)) {
        echo "Item updated successfully";
    } else {
        echo "Error updating item: " . mysqli_error($connection);
    }
    mysqli_stmt_close($stmt);
} else {
    echo "Error preparing statement: " . mysqli_error($connection);
}

// Close database connection
mysqli_close($connection);
?>
