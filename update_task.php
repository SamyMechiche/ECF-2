<?php
session_start();
include_once('./connexion.php');

if (!isset($_GET['task_id'])) {
    echo "ID de la tâche non fourni.";
    exit();
}

$task_id = $_GET['task_id'];

try {
    $sql = "SELECT * FROM `task` WHERE id_task = :task_id";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
    $stmt->execute();
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        echo "Tâche non trouvée.";
        exit();
    }
} catch (PDOException $e) {
    echo "Erreur lors de la récupération de la tâche : " . $e->getMessage();
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';
    $due_date = isset($_POST['due_date']) ? trim($_POST['due_date']) : '';

    if (!empty($name) && !empty($description) && !empty($due_date)) {
        try {
            // Mettre à jour les informations de la tâche
            $sql = "UPDATE `task` SET name = :name, description = :description, due_date = :due_date WHERE id_task = :task_id";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);
            $stmt->bindParam(':due_date', $due_date, PDO::PARAM_STR);
            $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
            $stmt->execute();

            header("Location: index.php");
            exit();
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour de la tâche : " . $e->getMessage();
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
    <title>Modifier la Tâche</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./asset\css\style.css">
</head>
<body>

<div class="container mt-5">
    <h2>Modifier la Tâche</h2>
    <form action="" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Nouveau nom</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($task['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <input type="text" class="form-control" id="description" name="description" value="<?php echo htmlspecialchars($task['description']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="due_date" class="form-label">Date butoire</label>
            <input type="date" class="form-control" id="due_date" name="due_date" value="<?php echo htmlspecialchars($task['due_date']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Je valide ces changements</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  
</body>
</html>
