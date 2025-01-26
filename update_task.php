<?php
session_start();
include_once('./connexion.php');

// Vérifie que task_id est dans l'URL
if (!isset($_GET['task_id'])) {
    echo "Task id not found";
    exit();
}

$task_id = $_GET['task_id'];

try {
    // On récup toutes les colonnes des tâches
    $sql = "SELECT * FROM `task` WHERE id_task = :task_id";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
    $stmt->execute();
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    // Existence de la tâche
    if (!$task) {
        echo "Task not found";
        exit();
    }
} catch (PDOException $e) {
    echo "An error occurred while getting the task" . $e->getMessage();
    exit();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $due_date = isset($_POST['due_date']) ? trim($_POST['due_date']) : '';

    // Validate form fields
    if (!empty($name) && !empty($description) && !empty($due_date)) {
        try {
            // on update la task
            $sql = "UPDATE `task` SET name = :name, description = :description, due_date = :due_date, updated = NOW() WHERE id_task = :task_id";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':due_date', $due_date, PDO::PARAM_STR);
            $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
            $stmt->execute();

            // Rdirige vers index quand task changée
            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            echo "An error occurred while updating the task" . $e->getMessage();
        }
    } else {
        echo "Fill up all the form";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Tâche</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./asset\css\style.css">
</head>
<body>

<div class="container mt-5">
    <div class="text-center mb-4">
        <img src="./asset\img\file-EAZkbufxqK3PMEF2xDQQDY 1.svg" alt="Logo TeamTasker" class="img-fluid" style="max-width: 150px;">
        <h2 class="green cerco size">UPDATE TASK</h2>
    </div>

<form action="./update_task.php?task_id=<?php echo htmlspecialchars($task_id); ?>" method="POST" class="needs-validation" novalidate>
    <div class="mb-3">
        <label for="name" class="form-label">Task name</label>
        <input type="text" class="form-control cerco-reg" id="name" name="name" value="<?php echo htmlspecialchars($task['name']); ?>" required>
        <div class="invalid-feedback">
            Please enter a task name
        </div>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <input type="text" class="form-control cerco-light" id="description" name="description" value="<?php echo htmlspecialchars($task['description']); ?>" required>
        <div class="invalid-feedback">
            Please enter a description for the task
        </div>
    </div>
    <div class="mb-3">
        <label for="due_date" class="form-label">Due date</label>
        <input type="date" class="form-control cerco-light" id="due_date" name="due_date" value="<?php echo htmlspecialchars($task['due_date']); ?>" required>
        <div class="invalid-feedback">
            Mention the due date of the task
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Change task !</button>
</form>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  
</body>
</html>