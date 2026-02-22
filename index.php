<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_res = $conn->query("SELECT username FROM users WHERE id=$user_id");
$user = $user_res->fetch_assoc();

$result = $conn->query("SELECT * FROM products");
$count_res = $conn->query("SELECT SUM(quantity) as total_items FROM cart_items WHERE user_id = $user_id");
$count_row = $count_res->fetch_assoc();
$cart_count = $count_row['total_items'] ?? 0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>Coffee Shop</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f1ea;
            margin:0;
        }

        .logo {
         font-size: 24px;  
        }

        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #6f4e37;
            padding: 15px 20px;
            color: white;
        }

        .right-menu {
            display: flex;
            align-items: center;
        }

        .cart-link {
            color: white;
            text-decoration: none;
            font-weight: bold;
            margin-right: 15px; 
        }

        .user-menu {
            position: relative;
        }

        .user-menu button {
            background: transparent;
            border: none;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            color: black;
            min-width: 150px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            border-radius: 5px;
            overflow: hidden;
            z-index: 10;
        }

        .dropdown a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: black;
        }

        .dropdown a:hover {
            background: #f0f0f0;
        }

        .container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
            padding: 15px;
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            cursor: pointer;
        }

        .card h3 {
            margin: 10px 0 5px;
        }

        .card p.price {
            font-weight: bold;
            color: #6f4e37;
            margin: 5px 0;
        }

        .card button {
            background: #6f4e37;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        .card button:hover {
            background: #5a3e2b;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">☕ Coffee Shop</div>

    <div class="right-menu">
        <a href="cart.php" class="cart-link">🛒 Cart (<?php echo $cart_count; ?>)</a>
        <div class="user-menu">
            <button onclick="toggleDropdown()"><?php echo htmlspecialchars($user['username']); ?> ▼</button>
            <div class="dropdown" id="dropdownMenu">
                <a href="profile.php">Profile</a>
                <a href="orders.php">Orders</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </div>
</div>

<h1 style="text-align:left; margin-top:25px; margin-left: 30px;, font-size: 18px; font-family: Arial, sans-serif;">Menu</h1>

<div class="container">
<?php while($row = $result->fetch_assoc()) { ?>
    <div class="card">
        <a href="product.php?id=<?php echo $row['id']; ?>">
            <img src="images/<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
        </a>
        <h3><?php echo $row['name']; ?></h3>
        <p class="price">₱<?php echo $row['price']; ?></p>
        <form method="POST" action="add_to_cart.php">
            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
            <button type="submit">Add to Cart</button>
        </form>
    </div>
<?php } ?>
</div>
<script>
function toggleDropdown() {
    var menu = document.getElementById('dropdownMenu');
    menu.style.display = (menu.style.display === "block") ? "none" : "block";
}

window.onclick = function(e) {
    if (!e.target.matches('.user-menu button')) {
        document.getElementById('dropdownMenu').style.display = "none";
    }
}
</script>

</body>
</html>