<?php
$page_title = "Edit Quiz";
require_once '../includes/header.php';

if(!isAdmin()) {
    redirect('../index.php');
}

$quiz_id = intval($_GET['quiz_id'] ?? 0);

// Get quiz info
$stmt = $conn->prepare("SELECT * FROM quizzes WHERE id = ?");
$stmt->bind_param("i", $quiz_id);
$stmt->execute();
$quiz = $stmt->get_result()->fetch_assoc();
$stmt->close();

if(!$quiz) {
    redirect('manage_quizzes.php');
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title']);
    $description = sanitizeInput($_POST['description']);
    
    $stmt = $conn->prepare("UPDATE quizzes SET title = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $title, $description, $quiz_id);
    
    if($stmt->execute()) {
        $_SESSION['success'] = "Quiz updated successfully!";
        redirect('manage_quizzes.php');
    } else {
        $error = "Error updating quiz: " . $conn->error;
    }
    $stmt->close();
}
?>

<div class="form-container">
    <h2>Edit Quiz</h2>
    
    <?php if(isset($error)): ?>
        <div class="alert error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <