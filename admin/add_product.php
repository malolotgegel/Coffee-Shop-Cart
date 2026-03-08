<?php
session_start();
if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../db.php");

if(isset($_POST['add'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageName = basename($_FILES['image']['name']);
        $targetDir = "../images/";
        $targetFile = $targetDir . $imageName;

        if(move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sdss", $name, $price, $description, $imageName);
            $stmt->execute();

            $_SESSION['success'] = "Product added successfully!";
            header("Location: products.php");
            exit();
        } else {
            $error = "Failed to upload image.";
        }
    } else {
        $error = "Please select an image.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Product - Admin</title>
    <style>
        body {
            font-family: Georgia, serif;
            background: #f5f1ea;
            color: #6f4e37;
            padding: 30px;
            max-width: 500px;
            margin: auto;
        }
        form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        input, textarea, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
        }
        .button {
            display: inline-block;
            padding: 8px 15px;
            background: #6f4e37;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 15px;
            font-family: Georgia, serif;
            transition: background 0.3s;
        }

        .button:hover {
            background: #5a3e2b;
        }
        
        button {
            background: #6f4e37;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #5a3e2b;
        }
        a {
            color: #6f4e37;
            display: inline-block;
            margin-top: 10px;
            text-decoration: none;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Add New Product</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="number" step="0.01" name="price" placeholder="Price (₱)" required>
        <input type="file" name="image" accept="image/*" required>
        <textarea name="description" placeholder="Product Description" required></textarea>
        <button type="submit" name="add">Add Product</button>
    </form>
<a href="products.php" class="button">← Back to Products</a>

    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
</body>
</html>