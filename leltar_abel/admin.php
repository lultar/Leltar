<?php
    require "db.php";

    $error_message = isset($_GET['error']) ? $_GET['error'] : '';

    $sql = "SELECT Users.UserID, Users.Username, Users.Password, UserTypes.UserTypeID, UserTypes.UserTypeName 
        FROM Users 
        INNER JOIN UserTypes ON Users.UserType = UserTypes.UserTypeID 
        WHERE UserTypeID = '2'";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="tab">
        <button class="tablinks" onclick="changeTab(event, 'Felhasznalo')">Felhasználók kezelése</button>
        <button class="tablinks" onclick="changeTab(event, 'Termek')">Termékek kezelése</button>
        <div class="dropdown" style="float: right;">
        <button class="tablinks">Admin</button>
        <div class="dropdown-content">
            <a href="#">Kijelentkezés</a>
        </div>
    </div>
    </div>

    <!-- Error message display -->
    <div id="error-alert" class="alert alert-danger" role="alert" style="display: none;">
        <?php echo htmlspecialchars($error_message); ?>
        <button type="button" class="close" onclick="closeAlert()">&times;</button>
    </div>

    <div id="Felhasznalo" class="tabcontent">
        <div class="user-management-container">
            <h3 class="user-management-title">Felhasználók kezelése</h3>
            <div class="add-user-button-container">
                <button type="button" class="add-user-button" onclick="rogzitModal()">+</button>
            </div>
        </div>
        <table class="table">
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
                        <td colspan="6">No admin users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div id="Termek" class="tabcontent">
        <h3>Termékek kezelése</h3>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Módosítás</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateForm" method="POST" action="modositas.php">
                        <input type="hidden" name="user_id" id="ModositasID">
                        <label for="Modositasname">Username:</label><br>
                        <input type="text" class="form-control" id="Modositasname" name="username"><br>
                        <label for="editPassword">Password:</label><br>
                        <input type="password" class="form-control" id="editPassword" name="password"><br>
                        <label for="ModositasType">UserType:</label><br>
                        <input type="text" class="form-control" id="ModositasType" name="user_type"><br><br>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal()">Mégse</button>
                    <button type="button" class="btn btn-primary" onclick="updateUser()">Mentés</button>
                </div>
                </div>
        </div>
    </div>

    <div class="modal fade" id="rogzitModal" tabindex="-1" role="dialog" aria-labelledby="rogzitModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rogzitModalLabel">Módosítás</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="updateForm" method="POST" action="modositas.php">
                        <input type="hidden" name="user_id" id="ModositasID">
                        <label for="NewUsername">Username:</label><br>
                        <input type="text" class="form-control" id="NewUsername" name="username"><br>
                        <label for="NewPassword">Password:</label><br>
                        <input type="text" class="form-control" id="NewPassword" name="password"><br>
                        <label for="NewUserType">UserType:</label><br>
                        <input type="text" class="form-control" id="NewUserType" name="user_type"><br><br>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModal()">Mégse</button>
                    <button type="button" class="btn btn-primary" onclick="rogzit()">Hozzáadás</button>
                </div>
                </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

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
            showConfirm("Biztosan törölni akarod ezt a felhasználót?", function(result) {
                if (result) {
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
            });
        }

        function Modositas(userID, username, password, userType) {
            document.getElementById('ModositasID').value = userID;
            document.getElementById('Modositasname').value = username;
            document.getElementById('editPassword').value = password;
            document.getElementById('ModositasType').value = userType;
            $('#editModal').modal('show');
        }

        function updateUser() {
            document.getElementById('updateForm').submit();
        }

        function closeModal() {
            $('#editModal').modal('hide');
            $('#rogzitModal').modal('hide');
        }

        function showConfirm(message, callback) {
            var overlay = document.createElement('div');
            overlay.className = 'confirm-overlay';

            var box = document.createElement('div');
            box.className = 'confirm-box';

            var msg = document.createElement('p');
            msg.textContent = message;

            var yesButton = document.createElement('button');
            yesButton.textContent = 'Igen';
            yesButton.className = 'yes-button';
            yesButton.onclick = function() {
                document.body.removeChild(overlay);
                callback(true);
            };

            var noButton = document.createElement('button');
            noButton.textContent = 'Nem';
            noButton.className = 'no-button';
            noButton.onclick = function() {
                document.body.removeChild(overlay);
                callback(false);
            };

            box.appendChild(msg);
            box.appendChild(yesButton);
            box.appendChild(noButton);
            overlay.appendChild(box);
            document.body.appendChild(overlay);
        }

        function closeAlert() {
            document.getElementById('error-alert').style.display = 'none';
        }

        function rogzitModal() {
            $('#rogzitModal').modal('show');
        }

        function rogzit() {
            var username = document.getElementById('NewUsername').value;
            var password = document.getElementById('NewPassword').value;
            var userType = document.getElementById('NewUserType').value;

            var xhr = new XMLHttpRequest();

            xhr.open('POST', 'rogzit.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status === 'success') {
                            location.reload();
                        } else {
                            document.getElementById('error-alert').innerHTML = response.message;
                            document.getElementById('error-alert').style.display = 'block';
                        }
                    }
                }
            };

            var data = 'username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password) + '&user_type=' + encodeURIComponent(userType);

            xhr.send(data);
        }

    </script>
</body>
</html>

<?php
    mysqli_close($connection);
?>
