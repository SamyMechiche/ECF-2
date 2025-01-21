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
    <title>TeamTasker | Homepage</title>
    <meta name="description" content="Homepage de TeamTasker">
    <link rel="stylesheet" href="./asset./css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
 
</head>
<body>

<?php if ($isLoggedIn): ?>
    <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
    <form method="POST">
        <button type="submit" name="logout">Se déconnecter</button>
    </form>
<?php else: ?>
    <a href="./login.php"><button>Login</button></a>
<?php endif; ?>

<h2>Tâches</h2>
<a href="./add_task.php"><button>Ajouter une tâche</button></a>
<ul>
    <?php foreach ($tasks as $task): ?>
        <div>
            <li>
                <strong><?php echo htmlspecialchars($task['name']); ?></strong><br>
                <?php echo htmlspecialchars($task['description']); ?><br>
                Date butoire: <?php echo htmlspecialchars($task['due_date']); ?>
            </li>
            <a href="#"><img src="./asset/img/trash-01.svg" alt="picto poubelle"></a>
            <a href="update_task.php?task_id=<?php echo $task['name']; ?>"><img src="./asset/img/settings-01.svg" alt="picto settings"></a>

        </div>
        
    <?php endforeach; ?>
</ul>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  
</body>
</html>

