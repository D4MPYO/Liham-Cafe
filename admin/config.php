<?php
$mysqli = new mysqli("localhost", "root", "", "db_liham-cafe");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
?>
