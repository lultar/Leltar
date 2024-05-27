<?php
$servername = "ssh.hueserver.hu";
$username = "leltar";
$password = "leltar123321";
$database = "leltar_db";

$connection = mysqli_connect($servername, $username, $password, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
