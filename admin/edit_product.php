<?php
session_start();
if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include("../db.php");
$id = intval($_GET['id'] ?? 0);
if($id <= 0){
    header("Location: products.php");
    exit();
}
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if($result->num_rows == 0){
    header("Location: products.php");
    exit();
}
$product = $result->fetch_assoc();

$error = "";

if(isset($_POST['update'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];

    if(isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $imageName = basename($_FILES['image']['name']);
        $targetDir = "../images/";
        $targetFile = $targetDir . $imageName;

        if(!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)){
            $error = "Failed to upload new image.";
        } else {
            $product['image'] = $imageName;
        }
    }

    if(empty($error)){
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sdsi", $name, $price, $product['image'], $id);
        $stmt->execute();

        header("Location: products.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product - Admin</title>
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
        input, button {
            width: 100%;
            padding: 12px;
            margin: 15px 0;
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
        img {
            max-width: 100px;
            border-radius: 8px;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h1>Edit Product</h1>
<form method="POST" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Product Name" required value="<?php echo htmlspecialchars($product['name']); ?>">
    <input type="number" step="0.01" name="price" placeholder="Price (₱)" required value="<?php echo htmlspecialchars($product['price']); ?>">
    
    <p>Current Image:</p>
    <img src="../images/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image">
    
    <p>Change Image (optional):</p>
    <input type="file" name="image" accept="image/*">
    
    <button type="submit" name="update">Update Product</button>
</form>

<a href="products.php" class="button">← Back to Products</a>

<?php if($error) echo "<div class='error'>$error</div>"; ?>

</body>
</html>