<?php
$page_title = "Add Questions";
require_once '../includes/header.php';

if(!isAdmin()) {
    redirect('../index.php');
}

$quiz_id = intval($_GET['quiz_id'] ?? 0);

// Get quiz info
$stmt = $conn->prepare("SELECT id, title FROM quizzes WHERE id = ?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$quiz = $stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$quiz) {
    redirect('dashboard.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $question_text = sanitizeInput($_POST['question_text']);
    $question_type = sanitizeInput($_POST['question_type']);
    
    // Insert question
    $stmt = $conn->prepare("INSERT INTO questions (quiz_id, question_text, question_type) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $quiz_id, $question_text, $question_type);
    $stmt->execute();
    $question_id = $conn->insert_id;
    $stmt->close();
    
    // Handle answers based on question type
    if($question_type == 'multiple_choice') {
        foreach($_POST['answers'] as $index => $answer_text) {
            if(!empty($answer_text)) {
                $is_correct = ($index == $_POST['correct_answer']) ? 1 : 0;
                $stmt = $conn->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, ?, ?)");
                $stmt->bind_param("isi", $question_id, $answer_text, $is_correct);
                $stmt->execute();
                $stmt->close();
            }
        }
    } else { // true_false
        // Add True option
        $stmt = $conn->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, 'True', ?)");
        $is_correct = ($_POST['true_false'] == 'true') ? 1 : 0;
        $stmt->bind_param("ii", $question_id, $is_correct);
        $stmt->execute();
        $stmt->close();
        
        // Add False option
        $stmt = $conn->prepare("INSERT INTO answers (question_id, answer_text, is_correct) VALUES (?, 'False', ?)");
        $is_correct = ($_POST['true_false'] == 'false') ? 1 : 0;
        $stmt->bind_param("ii", $question_id, $is_correct);
        $stmt->execute();
        $stmt->close();
    }
    
    $_SESSION['success'] = "Question added successfully!";
    redirect("add_questions.php?quiz_id=$quiz_id");
}
?>

<div class="form-container">
    <h2>Add Questions to: <?php echo htmlspecialchars($quiz['title']); ?></h2>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <form method="POST" id="questionForm">
        <div class="form-group">
            <label>Question Text</label>
            <textarea name="question_text" rows="3" required></textarea>
        </div>
        
        <div class="form-group">
            <label>Question Type</label>
            <select name="question_type" id="questionType" class="form-control" required>
                <option value="multiple_choice">Multiple Choice</option>
                <option value="true_false">True/False</option>
            </select>
        </div>
        
        <div id="multipleChoiceSection">
            <div class="form-group">
                <label>Answers</label>
                <div id="answerContainer">
                    <div class="answer-group">
                        <input type="text" name="answers[]" placeholder="Answer 1" class="answer-input" required>
                        <label class="correct-answer-label">
                            <input type="radio" name="correct_answer" value="0" required> Correct
                        </label>
                    </div>
                    <div class="answer-group">
                        <input type="text" name="answers[]" placeholder="Answer 2" class="answer-input" required>
                        <label class="correct-answer-label">
                            <input type="radio" name="correct_answer" value="1"> Correct
                        </label>
                    </div>
                </div>
                <button type="button" id="addAnswerBtn" class="btn btn-small">+ Add Another Answer</button>
            </div>
        </div>
        
        <div id="trueFalseSection" style="display: none;">
            <div class="form-group">
                <label>Correct Answer</label>
                <select name="true_false" class="form-control">
                    <option value="true">True</option>
                    <option value="false">False</option>
                </select>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Add Question</button>
            <a href="dashboard.php" class="btn btn-secondary">Finish</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const questionType = document.getElementById('questionType');
    const mcSection = document.getElementById('multipleChoiceSection');
    const tfSection = document.getElementById('trueFalseSection');
    const answerContainer = document.getElementById('answerContainer');
    const addAnswerBtn = document.getElementById('addAnswerBtn');
    
    // Toggle between question types
    questionType.addEventListener('change', function() {
        if(this.value === 'multiple_choice') {
            mcSection.style.display = 'block';
            tfSection.style.display = 'none';
        } else {
            mcSection.style.display = 'none';
            tfSection.style.display = 'block';
        }
    });
    
    // Add answer field
    addAnswerBtn.addEventListener('click', function() {
        const answerCount = document.querySelectorAll('.answer-group').length;
        const newAnswer = document.createElement('div');
        newAnswer.className = 'answer-group';
        newAnswer.innerHTML = `
            <input type="text" name="answers[]" placeholder="Answer ${answerCount + 1}" class="answer-input" required>
            <label class="correct-answer-label">
                <input type="radio" name="correct_answer" value="${answerCount}"> Correct
            </label>
        `;
        answerContainer.appendChild(newAnswer);
    });
});
</script>

<?php require_once '../includes/footer.php'; ?>