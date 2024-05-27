<?php
    require "db.php";

    $sql = "SELECT * FROM Users WHERE UserType = '2'";
    $result = mysqli_query($connection, $sql);

    $userData = [];

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $userData[] = $row;
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {font-family: Arial;}

        .tab {
            overflow: hidden;
            border: 1px solid #ccc;
            background-color: #f1f1f1;
        }

        .tab button {
            background-color: inherit;
            float: left;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 14px 16px;
            transition: 0.3s;
            font-size: 17px;
        }

        .tab button:hover {
            background-color: #ddd;
        }

        .tab button.active {
            background-color: #ccc;
        }

        .tabcontent {
            display: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            border-top: none;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table, .table th, .table td {
            border: 1px solid black;
        }

        .table th, .table td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="tab">
        <button class="tablinks" onclick="changeTab(event, 'Felhasznalo')">Felhasználók kezelése</button>
        <button class="tablinks" onclick="changeTab(event, 'Termek')">Termékek kezelése</button>
    </div>

    <div id="Felhasznalo" class="tabcontent">
        <h3>Felhasználók kezelése</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>UserID</th>
                    <th>Username</th>
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
                        <td><?php echo htmlspecialchars($user['UserType']); ?></td>
                        <td><button onclick="Modositas(<?php echo $user['UserID']; ?>)">Módosítás</button></td>
                        <td><button onclick="Torles(<?php echo $user['UserID']; ?>)">Törlés</button></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No admin users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div id="Termek" class="tabcontent">
        <h3>Termékek kezelése</h3>
    </div>

    <script>
        function changeTab(evt, cityName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tabcontent");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tablinks");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";
        }

        document.getElementsByClassName("tablinks")[0].click();

        function Torles(userID) {
            if (confirm("Are you sure you want to delete this user?")) {
                var form = document.createElement('form');
                form.method = 'POST';
                form.action = 'torles.php';

                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'user_id';
                input.value = userID;

                form.appendChild(input);
                document.body.appendChild(form);
                form.submit();
            }
        }

        function Modositas(userID) {
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = 'torles.php';

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'user_id';
            input.value = userID;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
</body>
</html>

