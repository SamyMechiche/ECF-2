<?php
session_start();
include_once('./connexion.php');

$isLoggedIn = isset($_SESSION['username']);

$tasks = [];
$shared_tasks = [];

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

try {
    $sql = "SELECT id_task, name, description, due_date FROM `task` ORDER BY `due_date`";
    $stmt = $bdd->prepare($sql);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql_shared = "SELECT id_task, name, description, due_date FROM `task` ORDER BY `due_date`";
    $stmt_shared = $bdd->prepare($sql_shared);
    $stmt_shared->execute();
    $shared_tasks = $stmt_shared->fetchAll(PDO::FETCH_ASSOC);

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./asset/css/style.css">
</head>
<body>

<nav class="navbar">
  <div class="container-fluid">
    <a class="navbar-brand">
      <img src="asset\img\file-EAZkbufxqK3PMEF2xDQQDY 1.svg" alt="Logo de teamtasker" width="50" height="50">
    </a>
    <div class="cerco size">
        <span class="green">T</span>eam<span class="blue">T</span>asker
    </div>
    <div class="d-flex">
        <?php if ($isLoggedIn): ?>
            <p class="m-0 me-3 cerco-reg"><?php echo ucfirst(htmlspecialchars($_SESSION['username'])); ?></p>
            <form method="POST" class="d-inline">
                <button type="submit" name="logout" class="btn btn-info">Logout</button>
            </form>
        <?php else: ?>
            <a href="./login.php" class="btn btn-info">Login</a>
        <?php endif; ?>
    </div>
  </div>
</nav>

<div class="todo">
    <h2 class="green cerco size">TO DO</h2>
    <a href="./add_task.php"><button type="submit" name="add" class="btn btn-info">Add a task</button></a>
</div>

<ul class="list-group flex">
    <?php foreach ($tasks as $task): ?>
        <li class="list-group-item">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($task['name']); ?></h5>
                    <p class="card-text"><span class="brown-dot"></span><?php echo htmlspecialchars($task['description']); ?></p>
                    <p class="card-text">Date butoire: <?php echo htmlspecialchars($task['due_date']); ?></p>
                    <button type="submit" name="done" class="btn btn-primary">DONE</button>
                    <div class="right">
                        <a href="delete_task.php?task_id=<?php echo $task['id_task']; ?>" class="card-link"><img src="./asset/img/trash-01.svg" alt="picto poubelle"></a>
                        <a href="update_task.php?task_id=<?php echo $task['id_task']; ?>" class="card-link"><img src="./asset/img/settings-01.svg" alt="picto settings"></a>
                    </div>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

<div class="shared-tasks">
    <h2 class="green cerco size">SHARED TASKS</h2>
    <a href="./add_task.php"><button type="submit" name="add" class="btn btn-info">Add a shared task</button></a>
</div>

<ul class="list-group flex">
    <?php foreach ($shared_tasks as $task): ?>
        <li class="list-group-item">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($task['name']); ?></h5>
                    <p class="card-text"><span class="brown-dot"></span><?php echo htmlspecialchars($task['description']); ?></p>
                    <p class="card-text">Date butoire: <?php echo htmlspecialchars($task['due_date']); ?></p>
                    <button type="submit" name="done" class="btn btn-primary">DONE</button>
                    <div class="right">
                        <a href="delete_task.php?task_id=<?php echo $task['id_task']; ?>" class="card-link"><img src="./asset/img/trash-01.svg" alt="picto poubelle"></a>
                        <a href="update_task.php?task_id=<?php echo $task['id_task']; ?>" class="card-link"><img src="./asset/img/settings-01.svg" alt="picto settings"></a>
                    </div>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>

<footer class="footer-dark">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-md-3 item">
                        <h3 class="cerco-reg">Services</h3>
                        <ul>
                            <li class="cerco-light"><a href="#">Nothing</a></li>
                            <li class="cerco-light"><a href="#">Nothing else</a></li>
                            <li class="cerco-light"><a href="#">Still nothing</a></li>
                        </ul>
                    </div>
                    <div class="col-sm-6 col-md-3 item">
                        <h3 class="cerco-reg">About</h3>
                        <ul>
                            <li class="cerco-light"><a href="#">Company</a></li>
                        </ul>
                    </div>
                    <div class="col-md-6 item text">
                        <h3 class="cerco-reg"><span>T</span>eam<span>T</span>asker</h3>
                        <p>TeamTasker is the optimum tool to manage and organize your task. We appreciate you using our services and we work everyday on making TeamTasker more attractive and useful !</p>
                    </div>
                    <div class="col item social">
                        <a href="#"><i class="icon ion-social-facebook"><i class="fa-brands fa-facebook"></i></i></a>
                        <a href="#"><i class="icon ion-social-twitter"><i class="fa-brands fa-x-twitter"></i></i></a>
                        <a href="#"><i class="icon ion-social-snapchat"><i class="fa-brands fa-snapchat"></i></i></a>
                        <a href="#"><i class="icon ion-social-instagram"><i class="fa-brands fa-square-instagram"></i></i></a>
                    </div>
                </div>
                <p class="copyright">TeamTasker © 2025</p>
            </div>
        </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  
</body>
</html>
