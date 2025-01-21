<?php
session_start();
include_once('./connexion.php');

$isLoggedIn = isset($_SESSION['username']);

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

try {
    // Récupérer les tâches depuis la base de données
    $sql = "SELECT * FROM `task` ORDER BY `due_date`";
    $stmt = $bdd->prepare($sql);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Erreur lors de la récupération des tâches : " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamTasker | Update a task</title>
    <meta name="description" content="Page de modification de tâche">
    <link rel="stylesheet" href="./asset./css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    
<form action="./add_task.php" method="POST">

    <label for="name">Nouveau nom: </label>
    <input type="text" name="name" id="name" required>

    <label for="description">Description: </label>
    <input type="text" name="description" id="description" required>

    <label for="due_date">Date butoire: </label>
    <input type="date" name="due_date" id="due_date" required>

    <input type="submit" value="Je valide ces changements">
</form>

</body>
</html>