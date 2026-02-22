<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$orders = $conn->query("
    SELECT * FROM orders 
    WHERE user_id = $user_id 
    ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order History</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f1ea;
            margin: 0;
        }

        .navbar {
            background: #6f4e37;
            padding: 15px;
            color: white;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        .container {
            width: 80%;
            max-width: 800px;
            margin: 30px auto;
        }

        .order-card {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .order-card p {
            margin: 8px 0;
        }

        .empty {
            text-align: center;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        .back-btn {
            display: inline-block;
            margin-bottom: 20px;
            background: #6f4e37;
            color: white;
            padding: 8px 15px;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-btn:hover {
            background: #5a3e2b;
        }
    </style>
</head>
<body>

<div class="navbar">
    ☕ Order History
</div>

<div class="container">

    <a href="index.php" class="back-btn">← Back to Shop</a>

    <?php if($orders->num_rows > 0) { ?>
        <?php while($row = $orders->fetch_assoc()) { ?>
            <div class="order-card">
                <p><strong>Order ID:</strong> <?php echo $row['id']; ?></p>
                <p><strong>Total:</strong> ₱<?php echo $row['total']; ?></p>
                <p><strong>Date:</strong> <?php echo $row['created_at']; ?></p>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="empty">
            <p>You have no orders yet.</p>
        </div>
    <?php } ?>

</div>

</body>
</html>