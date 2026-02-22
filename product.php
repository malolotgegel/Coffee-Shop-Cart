<?php
session_start();
include("db.php");

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM products WHERE id=$id");
$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $product['name']; ?> - Coffee Details</title>
    <style>
        body { font-family: Arial; background: #f4f1ea; text-align:center; padding:30px;}
        img { width:300px; border-radius:10px; }
        h1 { margin-top:20px; }
        p.price { font-weight:bold; color:#6f4e37; font-size:20px; }
        button { background:#6f4e37; color:white; padding:10px 20px; border:none; border-radius:5px; cursor:pointer; margin:10px;}
        button:hover { background:#5a3e2b; }
    </style>
</head>
<body>

<img src="images/<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
<h1><?php echo $product['name']; ?></h1>
<p><?php echo $product['description']; ?></p>
<p class="price">₱<?php echo $product['price']; ?></p>

<form method="POST" action="add_to_cart.php">
    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
    <button type="submit">Add to Cart</button>
</form>

<a href="index.php"><button>Back to Menu</button></a>

</body>
</html>