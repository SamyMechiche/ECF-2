<?php
session_start();
include_once('./connexion.php');

if (!isset($_SESSION['user_id'])) {
    echo '<div style="text-align: center; margin-top: 50px;">';
    echo '<h2 class="cerco">You must have logged in to acces this page</h2>';
    echo '<a href="login.php" class="cerco-light"style="display: inline-block; margin-top: 20px; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">LOGIN</a>';
    echo '</div>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $dueDate = isset($_POST['due_date']) ? trim($_POST['due_date']) : '';

    if (!empty($name) && !empty($description) && !empty($dueDate)) {
        try {
            $sqlTask = "INSERT INTO task (name, description, due_date, completed) 
                        VALUES (:name, :description, :due_date, 0)";
            $stmtTask = $bdd->prepare($sqlTask);
            $stmtTask->bindParam(':name', $name, PDO::PARAM_STR);
            $stmtTask->bindParam(':description', $description, PDO::PARAM_STR);
            $stmtTask->bindParam(':due_date', $dueDate, PDO::PARAM_STR);
            $stmtTask->execute();

            $taskId = $bdd->lastInsertId();

            // Match user id user task
            $sqlUserTask = "INSERT INTO user_task (id_user, id_task) VALUES (:id_user, :id_task)";
            $stmtUserTask = $bdd->prepare($sqlUserTask);
            $stmtUserTask->bindParam(':id_user', $_SESSION['user_id'], PDO::PARAM_INT);
            $stmtUserTask->bindParam(':id_task', $taskId, PDO::PARAM_INT);
            $stmtUserTask->execute();

            echo "<p style='font-family: 'CercoDEMO-Regular';'>Tâche ajoutée avec succès ! </p>";
        } catch (PDOException $e) {
            echo "<p style='font-family: 'CercoDEMO-Regular';'>Erreur lors de l'ajout de la tâche :  </p>" . $e->getMessage();
        }
    } else {
        echo "<p style='font-family: 'CercoDEMO-Regular';'>Veuillez remplir tous les champs. </p>";
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamTasker | Add a task</title>
    <meta name="description" content="Page d'ajout d'une tâche">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./asset./css/style.css">
    <link rel="shortcut icon" href="asset\img\file-EAZkbufxqK3PMEF2xDQQDY 1.svg" type="image/x-icon">
</head>
<body>

<div class="container mt-5">
    <div class="text-center mb-4">
        <img src="./asset\img\file-EAZkbufxqK3PMEF2xDQQDY 1.svg" alt="Logo TeamTasker" class="img-fluid" style="max-width: 150px;">
        <h2 class="green cerco size">ADD TASK</h2>
    </div>
    
<form action="./add_task.php" method="POST" class="needs-validation" novalidate>
    <div class="mb-3">
        <label for="name" class="form-label">Task name</label>
        <input type="text" class="form-control cerco-reg" id="name" name="name" required>
        <div class="invalid-feedback">
            Please enter a task name
        </div>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <input type="text" class="form-control cerco-light" id="description" name="description" required>
        <div class="invalid-feedback">
            Please enter a description for the task
        </div>
    </div>
    <div class="mb-3">
        <label for="due_date" class="form-label">Due date</label>
        <input type="date" class="form-control cerco-light" id="due_date" name="due_date" required>
        <div class="invalid-feedback">
            Mention the due date of the task
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Add a new task !</button>

    <div class="text-center mt-3">
        <a href="index.php" class="btn btn2 btn-secondary">Back to homepage</a>
    </div>
    
</form>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  
</body>
</html>