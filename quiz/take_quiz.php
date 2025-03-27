<?php
$page_title = "Take Quiz";
require_once '../includes/header.php';

if(!isLoggedIn()) {
    redirect('../auth/login.php');
}

$quiz_id = intval($_GET['quiz_id'] ?? 0);

// Get quiz info
$stmt = $conn->prepare("SELECT id, title, description FROM quizzes WHERE id = ?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$quiz = $stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$quiz) {
    redirect('quizzes.php');
}

// Check if user already took this quiz
$stmt = $conn->prepare("SELECT id FROM results WHERE user_id = ? AND quiz_id = ?");
$stmt->bind_param("ii", $_SESSION['user_id'], $quiz_id);
$stmt->execute();
$stmt->store_result();

if($stmt->num_rows > 0) {
    $_SESSION['info'] = "You've already taken this quiz. View your results below.";
    redirect("quiz_result.php?quiz_id=$quiz_id");
}
$stmt->close();

// Get questions
$stmt = $conn->prepare("SELECT id, question_text, question_type FROM questions WHERE quiz_id = ?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$questions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if(empty($questions)) {
    $_SESSION['error'] = "This quiz has no questions yet.";
    redirect('quizzes.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $score = 0;
    $total_questions = count($questions);
    
    foreach($questions as $question) {
        $user_answer = intval($_POST['question_'.$question['id']] ?? 0);
        
        if($user_answer) {
            $stmt = $conn->prepare("SELECT is_correct FROM answers WHERE id = ?");
            $stmt->bind_param("i", $user_answer);
            $stmt->execute();
            $is_correct = $stmt->get_result()->fetch_row()[0];
            $stmt->close();
            
            if($is_correct) {
                $score++;
            }
        }
    }
    
    // Save result
    $stmt = $conn->prepare("INSERT INTO results (user_id, quiz_id, score, total_questions) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiii", $_SESSION['user_id'], $quiz_id, $score, $total_questions);
    $stmt->execute();
    $stmt->close();
    
    redirect("quiz_result.php?quiz_id=$quiz_id");
}
?>

<div class="quiz-container">
    <h2><?php echo htmlspecialchars($quiz['title']); ?></h2>
    <p><?php echo htmlspecialchars($quiz['description']); ?></p>
    
    <form method="POST" id="quizForm">
        <?php foreach($questions as $question): ?>
            <div class="question-card">
                <h4><?php echo htmlspecialchars($question['question_text']); ?></h4>
                
                <?php
                $stmt = $conn->prepare("SELECT id, answer_text FROM answers WHERE question_id = ?");
                $stmt->bind_param("i", $question['id']);
                $stmt->execute();
                $answers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
                $stmt->close();
                
                foreach($answers as $answer): ?>
                    <div class="answer-option">
                        <label>
                            <input type="radio" name="question_<?php echo $question['id']; ?>" 
                                   value="<?php echo $answer['id']; ?>" required>
                            <?php echo htmlspecialchars($answer['answer_text']); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
        
        <div class="quiz-submit">
            <button type="submit" class="btn btn-primary">Submit Quiz</button>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>