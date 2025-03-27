<?php 
require_once __DIR__ . '/../config.php';
$page_title = $page_title ?? 'Quiz WebApp';
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link rel="stylesheet" href="/quiz webapp/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Add this to your existing header styles */
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
        min-height: 100vh; 
        }
        .content-wrap {
            flex: 1;
        }
    </style>
    <style>
        /* Responsive Navigation */
        .navbar {
            background: #2c3e50;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }
      
        
        .navbar-brand {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .navbar-brand i {
            margin-right: 10px;
        }

        .nav-links {
            display: flex;
            gap: 1.5rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 0.5rem 0;
            position: relative;
        }

        .nav-links a:hover {
            color: #4ecdc4;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: #4ecdc4;
            transition: width 0.3s;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .right-links {
            display: flex;
            gap: 1.5rem;
            align-items: center;
        }

        .right-links span {
            color: white;
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
                flex-wrap: wrap;
            }

            .nav-links {
                display: none;
                width: 100%;
                flex-direction: column;
                gap: 0;
                margin-top: 1rem;
            }

            .nav-links.active {
                display: flex;
            }

            .right-links {
                display: none;
                width: 100%;
                flex-direction: column;
                gap: 0;
            }

            .right-links.active {
                display: flex;
            }

            .nav-links a,
            .right-links a,
            .right-links span {
                padding: 0.8rem 0;
                width: 100%;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
            }

            .mobile-menu-btn {
                display: block;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="/quiz webapp/index.php" class="navbar-brand">
            <i class="fas fa-question-circle"></i> QuizApp
        </a>

        <div class="nav-links" id="navLinks">
            <?php if(isLoggedIn()): ?>
                <a href="/quiz webapp/quiz/quizzes.php">Quizzes</a>
                 <a href="/quiz webapp/index.php">Home</a>
                <?php if(isAdmin()): ?>
                    <a href="/quiz webapp/admin/dashboard.php">Admin Panel</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <div class="right-links" id="rightLinks">
            <?php if(isLoggedIn()): ?>
                <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="/quiz webapp/auth/logout.php">Logout</a>
            <?php else: ?>
                <a href="/quiz webapp/auth/login.php">Login</a>
                <a href="/quiz webapp/auth/register.php">Register</a>
                <a href="/quiz webapp/auth/admin_login.php">Admin Login</a>
            <?php endif; ?>
        </div>

        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <script>
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.getElementById('navLinks').classList.toggle('active');
            document.getElementById('rightLinks').classList.toggle('active');
        });
    </script>