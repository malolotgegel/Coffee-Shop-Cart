<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: orders.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = intval($_GET['id']);

$order_res = $conn->query("SELECT * FROM orders WHERE id=$order_id AND user_id=$user_id");
if($order_res->num_rows == 0){
    echo "Order not found or you don't have permission to view it.";
    exit();
}
$order = $order_res->fetch_assoc();

$items_res = $conn->query("
    SELECT oi.*, p.name, p.price 
    FROM order_items oi 
    LEFT JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id=$order_id
");
?>

<!DOCTYPE html>
<html>
<head>
    <title> Details</title>
    <style>
        body { font-family: Georgia, serif; background: #f5f1ea; color: #6f4e37; padding: 30px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #6f4e37; padding: 12px; text-align: center; }
        th { background: #6f4e37; color: white; }
        a.button { display: inline-block; padding: 6px 12px; border-radius: 6px; background: #6f4e37; color: white; text-decoration: none; margin: 2px; }
        a.button:hover { background: #5a3e2b; }
        .status-badge { padding: 4px 8px; border-radius: 6px; color: white; font-weight: bold; }
        .status-Pending { background: #f39c12; }
        .status-Preparing { background: #3498db; }
        .status-Ready { background: #16a085; }
        .status-Completed { background: #27ae60; }
        .status-Cancelled { background: #c0392b; }
        h1, h2 { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>

<h1>Details</h1>

<p><strong>Date:</strong> <?php echo $order['created_at']; ?></p>

<p><strong>Status:</strong> 
    <span class="status-badge status-<?php echo $order['status']; ?>">
        <?php echo htmlspecialchars($order['status']); ?>
        <?php if($order['status']=='Completed') echo ' ✔'; ?>
    </span>
</p>

<p><strong>Total:</strong> ₱<?php echo number_format($order['total'],2); ?></p>

<h2>Items in Order</h2>
<table>
    <thead>
        <tr>
            <th>Product Name</th>
            <th>Price (₱)</th>
            <th>Quantity</th>
            <th>Subtotal (₱)</th>
        </tr>
    </thead>
    <tbody>
        <?php while($item = $items_res->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['name']); ?></td>
            <td><?php echo number_format($item['price'],2); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td><?php echo number_format($item['price'] * $item['quantity'],2); ?></td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<a href="orders.php" class="button">← Back to Orders</a>

</body>
</html>