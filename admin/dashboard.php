<?php
session_start();
if(!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$admin_name = "Admin";
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard - Coffee Shop</title>

<style>

body{
    margin:0;
    font-family:'Segoe UI', sans-serif;

    background-image: url('images/bg2.jpg');
    background-size: flex;
    background-position: center;
    background-repeat: repeat;
}
.logo {
font-size: 28px; 
font-family: Georgia, serif;
color: #FFFFFF;
text-shadow: #FFF 0px 0px 5px, #FFF 0px 0px 10px, #FFF 0px 0px 15px, #e0b179 0px 0px 20px, #e0b179 0px 0px 30px, #e0b179 0px 0px 40px, #e0b179 0px 0px 50px, #e0b179 0px 0px 75px;
}

.topbar{
    height:60px;
    background:#6f4e37;
    color:white;
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:0 30px;
}

.admin{
    position:relative;
}

.admin button{
    background:none;
    border:none;
    color:white;
    font-size:16px;
    cursor:pointer;
}

.dropdown{
    display:none;
    position:absolute;
    right:0;
    background:white;
    min-width:140px;
    border-radius:8px;
    box-shadow:0 4px 10px rgba(0,0,0,0.2);
}

.dropdown a{
    display:block;
    padding:12px;
    text-decoration:none;
    color:#6f4e37;
}

.dropdown a:hover{
    background:#f1e5d8;
}

.admin:hover .dropdown{
    display:block;
}

.sidebar{
    width:220px;
    height:100vh;
    background:#5a3e2b;
    position:fixed;
    top:60px;
    left:0;
    padding-top:20px;
}

.sidebar a{
    display:block;
    color:white;
    padding:15px 25px;
    text-decoration:none;
}

.sidebar a:hover{
    background:#6f4e37;
}

.content{
    margin-left:220px;
    padding:80px 40px;
}

.content h1{
    font-size:45px;
    max-width:600px;
    text-align:center;
    color : #6f4e37;
}

.content p{
    font-size:24px;
    max-width:600px;
    text-align:center;
    color : #6f4e37;
}

.content ul{
    font-size:20px;
    max-width:600px;
    text-align:left;
    color : #6f4e37;
}
h1{
    color:#6f4e37;
}

</style>
</head>

<body>
    
<div class="topbar">

<div class='logo'>Bean & Bear</div>

<div class="admin">
<button><?php echo $admin_name; ?> ▼</button>

<div class="dropdown">
<a href="logout.php">Logout</a>
</div>

</div>

</div>

<div class="sidebar">

<a href="dashboard.php">Dashboard</a>
<a href="products.php">Products</a>
<a href="orders.php">Orders</a>
<a href="history.php">History</a>
</div>

<div class="content">

<h1>Welcome, Admin ☕</h1>
<p>Manage your coffee shop easily from the menu on the left. Here you can:</p>
    <ul>
        <li>Check and process new orders</li>
        <li>Manage your products</li>
        <li>View order history</li>
    </ul>
<p>Have a productive day! 🍩</p>
</div>

</body>
</html>