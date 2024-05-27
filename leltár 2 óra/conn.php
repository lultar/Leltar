<?php
$conn = new mysqli("ssh.hueserver.hu", "leltar", "leltar123321", "leltar_db");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>