<?php 
$page_title = "Create Quiz";
require_once '../includes/header.php'; 

if(!isAdmin()) {
    redirect('../index.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title']);
    $description = sanitizeInput($_POST['description']);
    $created_by = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("INSERT INTO quizzes (title, description, created_by) VALUES (?, ?, ?)");
    $stmt->bind_param("ssi", $title, $description, $created_by);
    
    if($stmt->execute()) {
        $quiz_id = $conn->insert_id;
        $_SESSION['success'] = "Quiz created successfully! Add questions now.";
        redirect("add_questions.php?quiz_id=$quiz_id");
    } else {
        $error = "Error creating quiz: " . $conn->error;
    }
    $stmt->close();
}
?>

<div class="form-container">
    <h2>Create New Quiz</h2>
    
    <?php if(isset($error)): ?>
        <div class="alert error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label>Quiz Title</label>
            <input type="text" name="title" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"></textarea>
        </div>
        <button type="submit" class="btn">Create Quiz</button>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>