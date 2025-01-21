<?php
session_start();
include_once('./connexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $date = isset($_POST['due_date']) ? trim($_POST['due_date']) : '';

    if (!empty($name) && !empty($description) && !empty($date)) {
        try {
            $sql = "INSERT INTO `task` (`name`, `description`, `due_date`) VALUES (:name, :description, :date)";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':date', $date, PDO::PARAM_STR);

            $stmt->execute();

            header('Location: index.php');
            exit();
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de la tâche : " . $e->getMessage();
        }
    } else {
        echo "Veuillez remplir tous les champs.";
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
</head>
<body>
    
<form action="./add_task.php" method="POST">

    <label for="name">Nom de la tâche: </label>
    <input type="text" name="name" id="name" required>

    <label for="description">Description: </label>
    <input type="text" name="description" id="description" required>

    <label for="due_date">Date butoire: </label>
    <input type="date" name="due_date" id="due_date" required>

    <input type="submit" value="J'ajoute cette tâche !">
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  
</body>
</html>