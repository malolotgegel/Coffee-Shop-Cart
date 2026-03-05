<?php
session_start();
include("db.php");

if(isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);
    if($stmt->execute()) {
        $_SESSION['user_id'] = $stmt->insert_id;
        header("Location: index.php");
        exit();
    } else {
        $error = "Username already exists!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Coffee Shop</title>
    <style>
        body {
            font-family:Georgia, serif;
            background-image: url('images/bg2.jpg'); 
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .register-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            width: 350px;
            text-align: center;
            border: 5px solid #6f4e37;
        }

        .register-card h2 {
            margin-bottom: 20px;
            color: #6f4e37;
        }

        .register-card input {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        .register-card button {
            width: 95%;
            padding: 12px;
            margin-top: 15px;
            border: none;
            border-radius: 8px;
            background: #6f4e37;
            color: white;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
            font-family:Georgia, serif;
        }

        .register-card button:hover {
            background: #5a3e2b;
        }

        .error {
            color: red;
            margin-top: 10px;
        }

        .login-link {
            margin-top: 15px;
            display: block;
            color: #6f4e37;
            text-decoration: none;
        }

        .login-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-card">
    <h2>Create Account</h2>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="register">Register</button>
    </form>
    <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
    <a href="login.php" class="login-link">Already have an account? Login</a>
</div>

</body>
</html>