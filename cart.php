<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$total = 0;
$cart_res = $conn->query("
    SELECT c.id AS cart_id, p.* , c.quantity 
    FROM cart_items c 
    JOIN products p ON c.product_id = p.id 
    WHERE c.user_id = $user_id
");
?>
<style>
body {
    font-family:Georgia, serif;
    background-image: url('images/bg2.jpg'); 
    margin: 0;
    padding: 20px;
}

h2 {
    text-align: center;
    color: #6b4226;
}

a {
    text-decoration: none;
    color: #6b4226;
    font-weight: bold;
}

.cart-item {
    display: flex;
    gap: 20px;
    background: #b57856b0;
    padding: 15px;
    margin-bottom: 15px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    align-items: center;
}

.cart-item img {
    border-radius: 10px;
}

.cart-item h3 {
    margin: 0 0 5px;
}

.cart-item p {
    margin: 5px 0;
}

input[type="number"] {
    width: 60px;
    padding: 5px;
    border-radius: 5px;
    border: 1px solid #ccc;
}

button {
    padding: 6px 12px;
    border: none;
    background: #6b4226;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    font-family:Georgia, serif;
}

button:hover {
    background: #8b5e3c;
}

hr {
    margin: 20px 0;
}

h3 {
    text-align: right;
}

.h3Title {
    text-align: left;
}

.checkout-btn {
    display: block;
    width: 200px;
    margin: 20px auto;
    padding: 10px;
    background: #875e1c;
    text-align: center;
    border-radius: 8px;
    font-family:Georgia, serif;
}

.checkout-btn button {
    width: 100%;
    background: transparent;
    color: white;
    font-size: 16px;
    font-family:Georgia, serif;
}
</style>
<h2>Your Cart</h2>
<a href="index.php">Back to Menu</a>
<hr>

<?php if($cart_res->num_rows > 0): ?>
    <?php while($row = $cart_res->fetch_assoc()): 
        $subtotal = $row['price'] * $row['quantity'];
        $total += $subtotal;
    ?>
        <div class="cart-item">
            <img src="images/<?php echo $row['image']; ?>" width="100">
            <div>
                <h3 class="h3Title"><?php echo $row['name']; ?></h3>
                <p><?php echo $row['description']; ?></p>
                <p>₱<?php echo $row['price']; ?> x 
                    <form method="POST" action="update_cart.php" style="display:inline;">
                        <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                        <input type="number" name="quantity" value="<?php echo $row['quantity']; ?>" min="1">
                        <button type="submit">Update</button>
                    </form>
                </p>
                <form method="POST" action="remove_from_cart.php">
                    <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                    <button type="submit">Remove</button>
                </form>
                <p>Subtotal: ₱<?php echo $subtotal; ?></p>
            </div>
        </div>
    <?php endwhile; ?>
    <h3>Total: ₱<?php echo $total; ?></h3>
<form method="POST" action="checkout.php" class="checkout-btn">
    <button type="submit">Checkout</button>
</form>
<?php else: ?>
    <p>Your cart is empty.</p>
<?php endif; ?>