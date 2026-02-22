<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(isset($_POST['cart_id'], $_POST['quantity'])){
    $cart_id = $_POST['cart_id'];
    $quantity = intval($_POST['quantity']);

    if($quantity < 1) $quantity = 1;

    $stmt = $conn->prepare("UPDATE cart_items SET quantity=? WHERE id=? AND user_id=?");
    $stmt->bind_param("iii", $quantity, $cart_id, $_SESSION['user_id']);
    $stmt->execute();
}

header("Location: cart.php");
exit();
?>