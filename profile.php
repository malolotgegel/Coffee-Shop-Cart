<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$query = $conn->prepare("SELECT * FROM users WHERE id=?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();


$success = $error = "";
if(isset($_POST['update_profile'])){
    $new_username = trim($_POST['username']);
    $new_password = trim($_POST['password']);

    if(empty($new_username)){
        $error = "Username cannot be empty.";
    } else {

        $stmt = $conn->prepare("UPDATE users SET username=? WHERE id=?");
        $stmt->bind_param("si", $new_username, $user_id);
        $stmt->execute();

        if(!empty($new_password)){
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt2 = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $stmt2->bind_param("si", $hashed_password, $user_id);
            $stmt2->execute();
        }

        $success = "Profile updated successfully!";
        $query->execute();
        $user = $query->get_result()->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile - Coffee Shop</title>
    <style>
        body { font-family: Arial; background: #f4f1ea; padding: 20px; }
        .profile-box {
            background: white; padding: 30px;
            border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            width: 400px; margin: 50px auto; text-align: center;
        }
        input { width: 90%; padding: 10px; margin: 10px 0; border-radius: 5px; border: 1px solid #ccc; }
        button { padding: 10px 20px; border: none; background: #6f4e37; color: white; border-radius: 8px; cursor: pointer; }
        button:hover { background: #5a3e2b; }
        .message { margin: 10px 0; color: green; }
        .error { margin: 10px 0; color: red; }
        a { display: inline-block; margin: 10px 5px; text-decoration: none; background: #6f4e37; color: white; padding: 8px 15px; border-radius: 8px; }
        a:hover { background: #5a3e2b; }
    </style>
</head>
<body>
<div class="profile-box">
    <h2>Edit Profile</h2>

    <?php if($success) echo "<div class='message'>$success</div>"; ?>
    <?php if($error) echo "<div class='error'>$error</div>"; ?>

    <form method="POST">
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="New Password (leave blank to keep current)"><br>
        <button type="submit" name="update_profile">Update Profile</button>
    </form>

    <a href="index.php">Back to Menu</a>
    <a href="logout.php">Logout</a>
</div>
</body>
</html>