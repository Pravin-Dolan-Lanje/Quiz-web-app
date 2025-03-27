<?php
$page_title = "Admin Login";
require_once __DIR__ . '/../config.php';

if(isLoggedIn() && isAdmin()) {
    redirect('../admin/dashboard.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? AND role = 'admin'");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        
        if(password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = 'admin';
            
            redirect('../admin/dashboard.php');
        } else {
            $error = "Invalid password";
        }
    } else {
        $error = "Admin account not found";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --accent-color: #4895ef;
            --dark-color: #1b263b;
            --light-color: #f8f9fa;
            --danger-color: #e63946;
            --success-color: #2a9d8f;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-image: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }
        
        .admin-login-container {
            width: 100%;
            max-width: 420px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            animation: fadeIn 0.5s ease-in-out;
        }
        
        .admin-header {
            background: var(--dark-color);
            color: white;
            padding: 1.5rem;
            text-align: center;
            position: relative;
        }
        
        .admin-header h2 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }
        
        .admin-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--accent-color);
            border-radius: 2px;
        }
        
        .admin-logo {
            width: 60px;
            height: 60px;
            margin: 0 auto 1rem;
            background: white;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        
        .admin-logo i {
            font-size: 2rem;
            color: var(--dark-color);
        }
        
        .admin-form {
            padding: 2rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: all 0.3s;
            background-color: #f8f9fa;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--accent-color);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
            background-color: white;
        }
        
        .btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: var(--dark-color);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn:hover {
            background: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(67, 97, 238, 0.3);
        }
        
        .alert {
            padding: 12px;
            margin-bottom: 1.5rem;
            border-radius: 6px;
            font-weight: 500;
        }
        
        .alert.error {
            background-color: #ffebee;
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }
        
        .admin-footer {
            text-align: center;
            padding: 1rem;
            font-size: 0.9rem;
            color: #666;
            border-top: 1px solid #eee;
        }
        
        .admin-footer a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Input icons */
        .input-icon {
            position: relative;
        }
        
        .input-icon i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #777;
        }
        
        .input-icon .form-control {
            padding-left: 45px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="admin-login-container">
        <div class="admin-header">
            <div class="admin-logo">
                <i class="fas fa-lock"></i>
            </div>
            <h2>Admin Portal</h2>
            <p>Access your administration dashboard</p>
        </div>
        
        <div class="admin-form">
            <?php if(isset($error)): ?>
                <div class="alert error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group input-icon">
                    <label for="username">Username</label>
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" class="form-control" placeholder="Enter admin username" required>
                </div>
                
                <div class="form-group input-icon">
                    <label for="password">Password</label>
                    <i class="fas fa-key"></i>
                    <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="btn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>
            </form>
        </div>
        
        <div class="admin-footer">
            <p>Not an admin? <a href="/quiz webapp/auth/login.php">User login</a></p>
        </div>
    </div>
</body>
</html>