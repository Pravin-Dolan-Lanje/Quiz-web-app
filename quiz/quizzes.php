<?php 
$page_title = "Available Quizzes";
require_once '../includes/header.php'; 

if(!isLoggedIn()) {
    redirect('../auth/login.php');
}

$stmt = $conn->prepare("SELECT q.id, q.title, q.description, u.username as creator 
                       FROM quizzes q 
                       JOIN users u ON q.created_by = u.id 
                       ORDER BY q.id DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Available Quizzes</h2>

<?php if($result->num_rows > 0): ?>
    <div class="quizzes-list">
        <?php while($quiz = $result->fetch_assoc()): ?>
            <div class="quiz-card">
                <h3><?php echo htmlspecialchars($quiz['title']); ?></h3>
                <p><?php echo htmlspecialchars($quiz['description']); ?></p>
                <p><small>Created by: <?php echo htmlspecialchars($quiz['creator']); ?></small></p>
                <a href="take_quiz.php?quiz_id=<?php echo $quiz['id']; ?>" class="btn">Take Quiz</a>
            </div>
        <?php endwhile; ?>
    </div>
<?php else: ?>
    <p>No quizzes available yet.</p>
    <?php if(isAdmin()): ?>
        <a href="../admin/create_quiz.php" class="btn">Create First Quiz</a>
    <?php endif; ?>
<?php endif; ?>

<?php require_once '../includes/footer.php'; ?>