<?php
session_start();
include("../db.php");

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? AND role='admin'");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();

    if($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        if(password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['id'];
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Wrong password!";
        }
    } else {
        $error = "Admin user not found!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login - Coffee Shop</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-image: url('images/bg2.jpg');
            background-size: cover;        
            background-position: center;    
            background-repeat: no-repeat;    
            display: flex;                   
            justify-content: center;
            align-items: center;
            height: 100vh;                
        }

        .logo-container img {
            width: 500px; 
            height: auto;
        }

        .container {
            display: flex;
            align-items: center;
            gap: 110px; 
        }

        .login-card {
            background: white;
            border: 5px solid #6f4e37;
            padding: 40px 30px 30px 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            width: 380px;
            text-align: center;
        }

        .login-card h2 {
            margin-bottom: 20px;
            color: #6f4e37;
        }

        .login-card input {
            width: 90%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
        }

        .login-card button {
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

        .login-card button:hover {
            background: #5a3e2b;
        }

        .error {
            margin-top: 10px;
            color: red;
        }

                @media (max-width: 800px) {
            .container {
                flex-direction: column;
            }
            .logo-container img {
                width: 400px;
                height: 500px;
                margin-bottom: 20px;
            }
        }
    </style>
</head>
<body>
 <div class="container">
        <div class="logo-container">
        <img src="images/bearLogo.png" alt="Coffee Shop Logo">
    </div>
    <div class="login-card">
        <h2>Admin Login</h2>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required autofocus>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="login">Login</button>
        </form>
        <?php if(isset($error)) echo "<div class='error'>$error</div>"; ?>
    </div>
</div>
</body>
</html>