<?php
$conn = new mysqli("localhost", "root", "", "coffeeshop");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>