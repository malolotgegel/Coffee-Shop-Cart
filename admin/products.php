<?php
session_start();
if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../db.php");

if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if($conn->query("DELETE FROM products WHERE id=$id")) {
        $_SESSION['success'] = "Product deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete product!";
    }
    header("Location: products.php");
    exit();
}

if(isset($_GET['toggle'])){
    $id = intval($_GET['toggle']);
    $row = $conn->query("SELECT status FROM products WHERE id=$id")->fetch_assoc();
    $new_status = $row['status'] == 'available' ? 'unavailable' : 'available';
    if($conn->query("UPDATE products SET status='$new_status' WHERE id=$id")){
        $_SESSION['success'] = "Product status updated!";
    } else {
        $_SESSION['error'] = "Failed to update status!";
    }
    header("Location: products.php");
    exit();
}

$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Products - Admin</title>
    <style>
        body {
            font-family: Georgia, serif;
            background: #f5f1ea;
            color: #6f4e37;
            padding: 30px;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
        }

        .top-links {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        a.button {
            display: inline-block;
            background: #6f4e37;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
            margin: 2px;
        }
        a.button:hover {
            background: #5a3e2b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #6f4e37;
            padding: 12px;
            text-align: center;
            vertical-align: middle;
        }
        th {
            background: #6f4e37;
            color: white;
        }
        img {
            width: 80px;
            border-radius: 6px;
        }
        td.actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 13px;
            color: white;
        }
        .available { background: green; }
        .unavailable { background: red; }

        .popup {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #6f4e37;
            color: white;
            padding: 20px 25px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            z-index: 999;
            opacity: 0;
            animation: fadeInOut 4s forwards;
        }
        .popup.error {
            background: #c0392b;
        }
        @keyframes fadeInOut {
            0% { opacity: 0; transform: translateY(-20px); }
            10% { opacity: 1; transform: translateY(0); }
            90% { opacity: 1; transform: translateY(0); }
            100% { opacity: 0; transform: translateY(-20px); }
        }
    </style>
</head>
<body>

<?php

if(isset($_SESSION['success'])) {
    echo '<div class="popup success">'.$_SESSION['success'].'</div>';
    unset($_SESSION['success']);
}
if(isset($_SESSION['error'])) {
    echo '<div class="popup error">'.$_SESSION['error'].'</div>';
    unset($_SESSION['error']);
}
?>

<h1>Manage Products</h1>

<div class="top-links">
    <a href="dashboard.php" class="button">← Dashboard</a>
    <a href="add_product.php" class="button">+ Add New Product</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Image</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price (₱)</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if($result->num_rows > 0) { ?>
            <?php while($product = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $product['id']; ?></td>
                    <td><img src="../images/<?php echo htmlspecialchars($product['image']); ?>" alt=""></td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['description']); ?></td>
                    <td><?php echo number_format($product['price'], 2); ?></td>
                    <td>
                        <span class="badge <?php echo $product['status']; ?>">
                            <?php echo ucfirst($product['status']); ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="button">Edit</a>
                        <a href="products.php?delete=<?php echo $product['id']; ?>" class="button" onclick="return confirm('Delete this product?')">Delete</a>
                        <a href="products.php?toggle=<?php echo $product['id']; ?>" class="button">
                            <?php echo $product['status']=='available'?'Disable':'Enable'; ?>
                        </a>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr><td colspan="7">No products found.</td></tr>
        <?php } ?>
    </tbody>
</table>

</body>
</html>