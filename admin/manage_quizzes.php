<?php
$page_title = "Manage Quizzes";
require_once '../includes/header.php';

if(!isAdmin()) {
    redirect('../index.php');
}

// Handle quiz deletion
if(isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    
    $stmt = $conn->prepare("DELETE FROM quizzes WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    $stmt->close();
    
    $_SESSION['success'] = "Quiz deleted successfully!";
    redirect('manage_quizzes.php');
}

// Get all quizzes
$stmt = $conn->prepare("SELECT q.id, q.title, q.description, u.username as creator, 
                       COUNT(qu.id) as question_count
                       FROM quizzes q
                       JOIN users u ON q.created_by = u.id
                       LEFT JOIN questions qu ON q.id = qu.quiz_id
                       GROUP BY q.id
                       ORDER BY q.id DESC");
$stmt->execute();
$quizzes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<div class="manage-container">
    <h2>Manage Quizzes</h2>
    
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <div class="table-responsive">
        <table class="quizzes-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Questions</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($quizzes as $quiz): ?>
                    <tr>
                        <td><?php echo $quiz['id']; ?></td>
                        <td><?php echo htmlspecialchars($quiz['title']); ?></td>
                        <td><?php echo htmlspecialchars($quiz['description']); ?></td>
                        <td><?php echo $quiz['question_count']; ?></td>
                        <td><?php echo htmlspecialchars($quiz['creator']); ?></td>
                        <td class="actions">
                            <a href="add_questions.php?quiz_id=<?php echo $quiz['id']; ?>" class="btn btn-small">Add Questions</a>
                            <a href="edit_quiz.php?quiz_id=<?php echo $quiz['id']; ?>" class="btn btn-small btn-secondary">Edit</a>
                            <a href="manage_quizzes.php?delete=<?php echo $quiz['id']; ?>" class="btn btn-small btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this quiz?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="add-new">
        <a href="create_quiz.php" class="btn btn-primary">Create New Quiz</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>