<?php
session_start();
require "db.php";

if (!isset($_SESSION['UserID']) || $_SESSION['UserType'] != 1) {
    header("Location: login.php");
    exit();
}

$error_message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';

function fetchUsers($connection) {
    $sql = "SELECT Users.UserID, Users.Username, Users.Password, UserTypes.UserTypeID, UserTypes.UserTypeName 
            FROM Users 
            INNER JOIN UserTypes ON Users.UserType = UserTypes.UserTypeID 
            WHERE UserTypeID = '2' 
            ORDER BY Users.UserID";
    $result = mysqli_query($connection, $sql);

    $userData = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $userData[] = $row;
        }
    }
    return $userData;
}

function fetchProducts($connection) {
    $sql = "SELECT Items.ItemID, Items.ItemName, Items.Description, Items.Quantity, Items.RealQuantity, MeasurementTypes.MeasurementType, Shelves.ShelfName 
            FROM Items 
            INNER JOIN MeasurementTypes ON Items.MeasurementTypeID = MeasurementTypes.MeasurementTypeID 
            INNER JOIN Shelves ON Items.ShelfID = Shelves.ShelfID 
            ORDER BY Items.ItemID";
    $result = mysqli_query($connection, $sql);

    $productData = [];
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $productData[] = $row;
        }
    }
    return $productData;
}

$username = isset($_SESSION['Username']) ? htmlspecialchars($_SESSION['Username']) : 'Admin';

$userData = fetchUsers($connection);
$productData = fetchProducts($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="tab">
        <button class="tablinks" onclick="changeTab(event, 'Felhasznalo')">Felhasználók kezelése</button>
        <button class="tablinks" onclick="changeTab(event, 'Termek')">Termékek kezelése</button>
        <div class="dropdown" style="float: right;">
            <button class="tablinks"><?php echo htmlspecialchars($username); ?></button>
            <div class="dropdown-content">
                <a href="login.php">Kijelentkezés</a>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    <?php if ($error_message): ?>
        <div id="error-alert" class="alert alert-danger" role="alert">
            <?php echo $error_message; ?>
            <button type="button" class="close" onclick="closeAlert()">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Users Management -->
    <div id="Felhasznalo" class="tabcontent">
        <div class="user-management-container">
            <h3 class="user-management-title">Felhasználók kezelése</h3>
            <div class="add-user-button-container">
                <select name="KeresSelect" id="KeresSelect">
                    <option value="UserID">UserID</option>
                    <option value="Username">Username</option>
                    <option value="Password">Password</option>
                    <option value="UserType">UserType</option>
                </select>
                <input type="search" placeholder="Keresés..." id="Kereses" class="searchbar" oninput="Keres()">
                <button type="button" class="add-user-button" onclick="rogzitModal()">+</button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th data-column="Users.UserID" data-dir="ASC" class="ths">UserID<i class="fa fa-sort"></i></th>
                    <th data-column="Users.Username" data-dir="ASC" class="ths">Username<i class="fa fa-sort"></i></th>
                    <th data-column="Users.Password" data-dir="ASC" class="ths">Password<i class="fa fa-sort"></i></th>
                    <th data-column="UserTypes.UserTypeName" data-dir="ASC" class="ths">UserType<i class="fa fa-sort"></i></th>
                    <th>Módosítás</th>
                    <th>Törlés</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($userData)): ?>
                    <?php foreach ($userData as $user): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['UserID']); ?></td>
                            <td><?php echo htmlspecialchars($user['Username']); ?></td>
                            <td><?php echo htmlspecialchars($user['Password']); ?></td>
                            <td><?php echo htmlspecialchars($user['UserTypeName']); ?></td>
                            <td><button onclick="Modositas(<?php echo $user['UserID']; ?>, '<?php echo htmlspecialchars($user['Username']); ?>', '<?php echo htmlspecialchars($user['Password']); ?>', '<?php echo htmlspecialchars($user['UserTypeID']); ?>')" class="btn btn-primary">Módosítás</button></td>
                            <td><button onclick="Torles(<?php echo $user['UserID']; ?>)" class="btn btn-danger">Törlés</button></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">Nincs találat.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Products Management -->
    <div id="Termek" class="tabcontent">
        <div class="user-management-container">
            <h3 class="user-management-title">Termékek kezelése</h3>
            <div class="add-user-button-container">
                <select name="TKeresSelect" id="TKeresSelect">
                    <option value="ItemID">ItemID</option>
                    <option value="ItemName">ItemName</option>
                    <option value="Description">Description</option>
                    <option value="Quantity">Quantity</option>
                    <option value="RealQuantity">RealQuantity</option>
                    <option value="MeasurementType">MeasurementType</option>
                    <option value="ShelfName">ShelfName</option>
                </select>
                <input type="search" placeholder="Keresés..." id="ProductSearch" class="searchbar" oninput="KeresS()">
                <button type="button" class="add-user-button" onclick="showAddProductModal()">+</button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr>
                    <th data-column="Items.ItemID" data-dir="ASC" class="ths">ItemID<i class="fa fa-sort"></i></th>
                    <th data-column="Items.ItemName" data-dir="ASC" class="ths">ItemName<i class="fa fa-sort"></i></th>
                    <th data-column="Items.Description" data-dir="ASC" class="ths">Description<i class="fa fa-sort"></i></th>
                    <th data-column="Items.Quantity" data-dir="ASC" class="ths">Quantity<i class="fa fa-sort"></i></th>
                    <th data-column="Items.RealQuantity" data-dir="ASC" class="ths">RealQuantity<i class="fa fa-sort"></i></th>
                    <th data-column="MeasurementTypes.MeasurementType" data-dir="ASC" class="ths">MeasurementType<i class="fa fa-sort"></i></th>
                    <th data-column="Shelves.ShelfName" data-dir="ASC" class="ths">ShelfName<i class="fa fa-sort"></i></th>
                    <th>Módosítás</th>
                    <th>Törlés</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($productData)): ?>
                    <?php foreach ($productData as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['ItemID']); ?></td>
                            <td><?php echo htmlspecialchars($product['ItemName']); ?></td>
                            <td><?php echo htmlspecialchars($product['Description']); ?></td>
                            <td><?php echo htmlspecialchars($product['Quantity']); ?></td>
                            <td><?php echo htmlspecialchars($product['RealQuantity']); ?></td>
                            <td><?php echo htmlspecialchars($product['MeasurementType']); ?></td>
                            <td><?php echo htmlspecialchars($product['ShelfName']); ?></td>
                            <td><button onclick="productModositas(<?php echo $product['ItemID']; ?>, '<?php echo htmlspecialchars($product['ItemName']); ?>', '<?php echo htmlspecialchars($product['Description']); ?>', '<?php echo htmlspecialchars($product['Quantity']); ?>', '<?php echo htmlspecialchars($product['RealQuantity']); ?>', '<?php echo htmlspecialchars($product['MeasurementType']); ?>', '<?php echo htmlspecialchars($product['ShelfName']); ?>')" class="btn btn-primary">Módosítás</button></td>
                            <td><button onclick="productTorles(<?php echo $product['ItemID']; ?>)" class="btn btn-danger">Törlés</button></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">Nincs találat.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Modals -->
    <!-- Add User Modal -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Felhasználó hozzáadása</h2>
            <form action="rogzit.php" method="post">
                <label for="addUsername">Felhasználónév:</label>
                <input type="text" id="addUsername" name="username" required>
                <label for="addPassword">Jelszó:</label>
                <input type="password" id="addPassword" name="password" required>
                <label for="addUserType">Felhasználó típus:</label>
                <select id="addUserType" name="usertype" required>
                    <option value="1">Admin</option>
                    <option value="2">User</option>
                </select>
                <input type="submit" value="Hozzáadás">
            </form>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Termék hozzáadása</h2>
            <form action="addProduct.php" method="post">
                <label for="addItemName">Termék neve:</label>
                <input type="text" id="addItemName" name="itemname" required>
                <label for="addDescription">Leírás:</label>
                <input type="text" id="addDescription" name="description" required>
                <label for="addQuantity">Mennyiség:</label>
                <input type="number" id="addQuantity" name="quantity" required>
                <label for="addRealQuantity">Valós mennyiség:</label>
                <input type="number" id="addRealQuantity" name="realquantity" required>
                <label for="addMeasurementType">Mértékegység:</label>
                <input type="text" id="addMeasurementType" name="measurementtype" required>
                <label for="addShelfName">Polc neve:</label>
                <input type="text" id="addShelfName" name="shelfname" required>
                <input type="submit" value="Hozzáadás">
            </form>
        </div>
    </div>

    <script src="admin.js"></script>
</body>
</html>
