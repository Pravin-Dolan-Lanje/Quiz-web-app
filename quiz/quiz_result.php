<?php
$page_title = "Quiz Results";
require_once '../includes/header.php';

if(!isLoggedIn()) {
    redirect('../auth/login.php');
}

$quiz_id = intval($_GET['quiz_id'] ?? 0);

// Get quiz info
$stmt = $conn->prepare("SELECT id, title FROM quizzes WHERE id = ?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$quiz = $stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$quiz) {
    redirect('quizzes.php');
}

// Get user's most recent result for this quiz
$stmt = $conn->prepare("SELECT * FROM results 
                       WHERE user_id = ? AND quiz_id = ? 
                       ORDER BY taken_at DESC LIMIT 1");
$stmt->bind_param("ii", $_SESSION['user_id'], $quiz_id);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$result) {
    redirect('quizzes.php');
}

$percentage = round(($result['score'] / $result['total_questions']) * 100, 2);
?>

<div class="result-container">
    <h2>Quiz Results: <?php echo htmlspecialchars($quiz['title']); ?></h2>
    
    <div class="result-summary">
        <div class="result-card">
            <h3>Your Score</h3>
            <div class="score-display">
                <span class="score"><?php echo $result['score']; ?></span>
                <span class="total">/ <?php echo $result['total_questions']; ?></span>
            </div>
            <div class="percentage"><?php echo $percentage; ?>%</div>
        </div>
        
        <div class="result-details">
            <h3>Performance</h3>
            <?php if($percentage >= 80): ?>
                <p class="excellent">Excellent work! You've mastered this material.</p>
            <?php elseif($percentage >= 60): ?>
                <p class="good">Good job! You have a solid understanding.</p>
            <?php elseif($percentage >= 40): ?>
                <p class="average">Not bad! Consider reviewing the material.</p>
            <?php else: ?>
                <p class="poor">Keep practicing! You'll improve with more study.</p>
            <?php endif; ?>
            
            <p>Quiz taken on: <?php echo date('F j, Y g:i a', strtotime($result['taken_at'])); ?></p>
        </div>
    </div>
    
    <div class="action-buttons">
        <a href="quizzes.php" class="btn btn-primary">Back to Quizzes</a>
        <?php if(isAdmin()): ?>
            <a href="../admin/manage_quizzes.php" class="btn btn-secondary">Manage Quizzes</a>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>