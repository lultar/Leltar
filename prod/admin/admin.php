<?php
    session_start();
    require "db.php";

    $error_message = isset($_GET['error']) ? $_GET['error'] : '';

    $sql = "SELECT Users.UserID, Users.Username, Users.Password, UserTypes.UserTypeID, UserTypes.UserTypeName FROM Users INNER JOIN UserTypes ON Users.UserType = UserTypes.UserTypeID WHERE UserTypeID = '2' ORDER BY Users.UserID";
    $result = mysqli_query($connection, $sql);

    $userData = [];

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $userData[] = $row;
        }
    }

    $sql2 = "SELECT Items.ItemID, Items.ItemName, Items.Description, Items.Quantity, Items.RealQuantity, MeasurementTypes.MeasurementType, Shelves.ShelfName 
        FROM Items 
        INNER JOIN MeasurementTypes ON Items.MeasurementTypeID = MeasurementTypes.MeasurementTypeID 
        INNER JOIN Shelves ON Items.ShelfID = Shelves.ShelfID ORDER BY Items.ItemID";
    $result = mysqli_query($connection, $sql2);

    $productData = [];

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $productData[] = $row;
        }
    }

    if (!isset($_SESSION['UserID']) || $_SESSION['UserType'] != 1) {
        header("Location: login.php");
        exit();
    }
    $username = isset($_SESSION['Username']) ? $_SESSION['Username'] : 'Admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minimalist Design</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
        }
        .tab {
            display: flex;
            justify-content: space-between;
            background-color: #007bff;
            padding: 10px;
        }
        .tab button {
            background-color: inherit;
            border: none;
            outline: none;
            color: white;
            padding: 14px 16px;
            cursor: pointer;
            transition: 0.3s;
        }
        .tab button:hover {
            background-color: #0056b3;
        }
        .tab button.active {
            background-color: #0056b3;
        }
        .tabcontent {
            display: none;
            padding: 20px;
        }
        .tabcontent.active {
            display: block;
        }
        .user-management-container {
            margin-bottom: 20px;
        }
        .user-management-title {
            margin-bottom: 10px;
            font-size: 1.5rem;
            color: #007bff;
        }
        .add-user-button-container {
            display: flex;
            gap: 10px;
        }
        .add-user-button {
            padding: 6px 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .add-user-button:hover {
            background-color: #0056b3;
        }
        .table th, .table td {
            vertical-align: middle;
        }
        .modal-header, .modal-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header .close, .modal-footer .btn {
            margin-left: 10px;
        }
        .alert {
            display: none;
        }
        .confirm-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .confirm-box {
            background-color: white;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
        }
        .confirm-box button {
            margin: 10px;
        }
    </style>
</head>
<body>
    <div class="tab">
        <div>
            <button class="tablinks" onclick="changeTab(event, 'Felhasznalo')">Felhasználók kezelése</button>
            <button class="tablinks" onclick="changeTab(event, 'Termek')">Termékek kezelése</button>
        </div>
        <div class="dropdown">
            <button class="tablinks"><?php echo htmlspecialchars($username); ?></button>        
            <div class="dropdown-content">
                <a href="login.php">Kijelentkezés</a>
            </div>
        </div>
    </div>

    <div id="error-alert" class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($error_message); ?>
        <button type="button" class="close" onclick="closeAlert()">&times;</button>
    </div>

    <div id="Felhasznalo" class="tabcontent">
        <div class="user-management-container">
            <h3 class="user-management-title">Felhasználók kezelése</h3>
            <div class="add-user-button-container">
                <select name="KeresSelect" id="KeresSelect" class="form-select">
                    <option value="UserID">UserID</option>
                    <option value="Username">Username</option>
                    <option value="Password">Password</option>
                    <option value="UserType">UserType</option>
                </select>
                <input type="search" placeholder="Keresés..." id="Kereses" class="form-control" oninput="Keres()">
                <button type="button" class="add-user-button" onclick="rogzitModal()">+</button>
            </div>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>UserID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>UserType</th>
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

    <div id="Termek" class="tabcontent">
        <div class="user-management-container">
            <h3 class="user-management-title">Termékek kezelése</h3>
            <div class="add-user-button-container">
                <select name="TKeresSelect" id="TKeresSelect" class="form-select">
                    <option value="ItemID">ItemID</option>
                    <option value="ItemName">ItemName</option>
                    <option value="Description">Description</option>
                    <option value="Quantity">Quantity</option>
                    <option value="RealQuantity">RealQuantity</option>
                    <option value="MeasurementType">MeasurementType</option>
                    <option value="ShelfName">ShelfName</option>
                </select>
                <input type="search" placeholder="Keresés..." id="ProductSearch" class="form-control" oninput="KeresS()">
                <button type="button" class="add-user-button" onclick="showAddProductModal()">+</button>
            </div>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ItemID</th>
                    <th>ItemName</th>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>RealQuantity</th>
                    <th>MeasurementType</th>
                    <th>ShelfName</th>
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
                            <td><button onclick="editProduct(<?php echo $product['ItemID']; ?>, '<?php echo htmlspecialchars($product['ItemName']); ?>', '<?php echo htmlspecialchars($product['Description']); ?>', <?php echo $product['Quantity']; ?>, <?php echo $product['RealQuantity']; ?>, '<?php echo htmlspecialchars($product['MeasurementType']); ?>', '<?php echo htmlspecialchars($product['ShelfName']); ?>')" class="btn btn-primary">Módosítás</button></td>
                            <td><button onclick="deleteProduct(<?php echo $product['ItemID']; ?>)" class="btn btn-danger">Törlés</button></td>
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

    <!-- Modal for adding/editing users -->
    <div id="myModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Felhasználó hozzáadása/módosítása</h5>
                    <button type="button" class="close" onclick="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="addUserForm" method="post" action="rogzit.php">
                        <div class="mb-3">
                            <label for="UserID" class="form-label">UserID</label>
                            <input type="number" class="form-control" id="UserID" name="UserID" required>
                        </div>
                        <div class="mb-3">
                            <label for="Username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="Username" name="Username" required>
                        </div>
                        <div class="mb-3">
                            <label for="Password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="Password" name="Password" required>
                        </div>
                        <div class="mb-3">
                            <label for="UserType" class="form-label">UserType</label>
                            <input type="number" class="form-control" id="UserType" name="UserType" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for adding/editing products -->
    <div id="productModal" class="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Termék hozzáadása/módosítása</h5>
                    <button type="button" class="close" onclick="closeProductModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm" method="post" action="rogzit_termek.php">
                        <div class="mb-3">
                            <label for="ItemID" class="form-label">ItemID</label>
                            <input type="number" class="form-control" id="ItemID" name="ItemID" required>
                        </div>
                        <div class="mb-3">
                            <label for="ItemName" class="form-label">ItemName</label>
                            <input type="text" class="form-control" id="ItemName" name="ItemName" required>
                        </div>
                        <div class="mb-3">
                            <label for="Description" class="form-label">Description</label>
                            <textarea class="form-control" id="Description" name="Description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="Quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="Quantity" name="Quantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="RealQuantity" class="form-label">RealQuantity</label>
                            <input type="number" class="form-control" id="RealQuantity" name="RealQuantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="MeasurementType" class="form-label">MeasurementType</label>
                            <input type="text" class="form-control" id="MeasurementType" name="MeasurementType" required>
                        </div>
                        <div class="mb-3">
                            <label for="ShelfName" class="form-label">ShelfName</label>
                            <input type="text" class="form-control" id="ShelfName" name="ShelfName" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" onclick="closeProductModal()">Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (<?php echo json_encode($error_message) ?>) {
                document.getElementById('error-alert').style.display = 'block';
            }
        });

        function closeAlert() {
            document.getElementById('error-alert').style.display = 'none';
        }

        function changeTab(evt, tabName) {
            const tabcontent = document.getElementsByClassName("tabcontent");
            for (let i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            const tablinks = document.getElementsByClassName("tablinks");
            for (let i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        function rogzModal() {
            document.getElementById('myModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('myModal').style.display = 'none';
        }

        function showAddProductModal() {
            document.getElementById('productModal').style.display = 'block';
        }

        function closeProductModal() {
            document.getElementById('productModal').style.display = 'none';
        }
    </script>
</body>
</html>
