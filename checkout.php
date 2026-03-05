<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include("db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$cart_res = $conn->query("SELECT * FROM cart_items WHERE user_id=$user_id");

if($cart_res->num_rows == 0){
    echo "Your cart is empty.<br>";
    echo "<a href='index.php'>Back to Menu</a>";
    exit();
}

$total = 0;
while($item = $cart_res->fetch_assoc()){
    $product_id = $item['product_id'];
    
    $prod_res = $conn->query("SELECT price FROM products WHERE id=$product_id");
    if($prod_res->num_rows > 0){
        $product = $prod_res->fetch_assoc();
        $total += $item['quantity'] * $product['price'];
    } else {
        echo "Product ID $product_id not found!";
        exit();
    }
}

$order_stmt = $conn->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
$order_stmt->bind_param("id", $user_id, $total);
$order_stmt->execute();
$order_id = $conn->insert_id;


$cart_res = $conn->query("SELECT * FROM cart_items WHERE user_id=$user_id");
$order_item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");

while($item = $cart_res->fetch_assoc()){
    $order_item_stmt->bind_param("iii", $order_id, $item['product_id'], $item['quantity']);
    $order_item_stmt->execute();
}


$conn->query("DELETE FROM cart_items WHERE user_id=$user_id");

?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout Successful</title>
    <style>
        body {
            font-family: Georgia, serif;
            background-image: url('images/bg2.jpg'); 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column;
        }
        .success-box {
            background: white;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            text-align: center;
            border: 5px solid #6f4e37;
        }
        .success-box a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #6f4e37;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }
        .success-box a:hover {
            background: #5a3e2b;
        }
        h2{
            font-family: Georgia, serif;
        }
    </style>
</head>
<body>
<div class="success-box">
    <h2>Checkout Successful!</h2>
    <p>Your order ID is <strong>#<?php echo $order_id; ?></strong></p>
    <p>Total: ₱<?php echo $total; ?></p>
    <a href="index.php">Back to Menu</a>
</div>
</body>
</html>