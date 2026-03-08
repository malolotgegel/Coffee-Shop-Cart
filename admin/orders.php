<?php
session_start();
if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../db.php");

if(isset($_GET['done'])) {
    $order_id = intval($_GET['done']);
    $conn->query("UPDATE orders SET status='Completed' WHERE id=$order_id");
    header("Location: orders.php");
    exit();
}
$result = $conn->query("
    SELECT orders.*, users.username 
    FROM orders 
    LEFT JOIN users ON orders.user_id = users.id 
    WHERE orders.status IS NULL OR orders.status != 'Completed'
    ORDER BY orders.created_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Orders - Admin</title>
    <style>
        body {
            font-family: Georgia, serif;
            background: #f5f1ea;
            color: #6f4e37;
            padding: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #6f4e37;
            padding: 12px;
            text-align: center;
        }

        th {
            background: #6f4e37;
            color: white;
        }

        a.button {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            background: #6f4e37;
            color: white;
            text-decoration: none;
            margin: 2px;
        }

        a.button:hover {
            background: #5a3e2b;
        }
    </style>
</head>
<body>
    <h1>All Orders</h1>
    <a href="dashboard.php" class="button">← Back to Dashboard</a>

    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer Username</th>
                <th>Total (₱)</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while($order = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $order['id']; ?></td>
                    <td><?php echo htmlspecialchars($order['username']); ?></td>
                    <td><?php echo number_format($order['total'], 2); ?></td>
                    <td><?php echo $order['created_at']; ?></td>
                    <td>
                        <a href="view_order.php?id=<?php echo $order['id']; ?>" class="button">View</a>
                        <a href="orders.php?done=<?php echo $order['id']; ?>" class="button">Done</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>