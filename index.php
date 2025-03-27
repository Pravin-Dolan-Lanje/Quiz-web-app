<?php
$page_title = "Home";
require_once __DIR__ . '/includes/header.php';
?>

<div class="welcome-section">
    <h1>Welcome to Quiz WebApp</h1>
    
    <?php if(isLoggedIn()): ?>
        <div class="welcome-message">
            <p>Hello <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            <p>Test your knowledge with our interactive quizzes.</p>
            <div class="action-buttons">
                <a href="quiz/quizzes.php" class="btn btn-primary">Browse Quizzes</a>
                <?php if(isAdmin()): ?>
                    <a href="admin/dashboard.php" class="btn btn-secondary">Admin Dashboard</a>
                <?php endif; ?>
            </div>
        </div>
    <?php else: ?>
        <div class="guest-message">
            <p>Please login or register to access our quiz collection.</p>
            <div class="action-buttons">
                <a href="auth/login.php" class="btn btn-primary">Login</a>
                <a href="auth/register.php" class="btn btn-secondary">Register</a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>