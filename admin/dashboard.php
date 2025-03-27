<?php 
$page_title = "Admin Dashboard";
require_once '../includes/header.php'; 

if(!isAdmin()) {
    redirect('../index.php');
}
?>

<h2>Admin Dashboard</h2>

<div class="dashboard-grid">
    <div class="dashboard-card">
        <h3>Quizzes</h3>
        <?php
        $stmt = $conn->prepare("SELECT COUNT(*) FROM quizzes");
        $stmt->execute();
        $count = $stmt->get_result()->fetch_row()[0];
        $stmt->close();
        ?>
        <p>Total: <?php echo $count; ?></p>
        <a href="manage_quizzes.php" class="btn">Manage Quizzes</a>
    </div>
    
    <div class="dashboard-card">
        <h3>Users</h3>
        <?php
        $stmt = $conn->prepare("SELECT COUNT(*) FROM users");
        $stmt->execute();
        $count = $stmt->get_result()->fetch_row()[0];
        $stmt->close();
        ?>
        <p>Total: <?php echo $count; ?></p>
    </div>
    
    <div class="dashboard-card">
        <h3>Create New Quiz</h3>
        <a href="create_quiz.php" class="btn">Create Quiz</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>