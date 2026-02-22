<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = (int)$_POST['id'];

$stmt = $conn->prepare("SELECT * FROM cart_items WHERE user_id=? AND product_id=?");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();
$res = $stmt->get_result();

if($res->num_rows > 0) {
    $item = $res->fetch_assoc();
    $qty = $item['quantity'] + 1;
    $stmt2 = $conn->prepare("UPDATE cart_items SET quantity=? WHERE id=?");
    $stmt2->bind_param("ii", $qty, $item['id']);
    $stmt2->execute();
} else {
    $stmt2 = $conn->prepare("INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?,?,1)");
    $stmt2->bind_param("ii", $user_id, $product_id);
    $stmt2->execute();
}

header("Location: cart.php");
exit();