<?php
session_start();
include_once('./connexion.php');

// Check si user est log
$isLoggedIn = isset($_SESSION['username']) && isset($_SESSION['user_id']);
$userId = $_SESSION['user_id']; // Récup l'id de l'user
$tasks = [];
$shared_tasks = [];

// Logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

// Check si checked est checké
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task_id'])) {
    $taskId = intval($_POST['task_id']);
    $isCompleted = isset($_POST['completed']) ? 1 : 0;

    try {
        $updateSql = "UPDATE `task` SET `completed` = :completed WHERE `id_task` = :id_task";
        $stmt = $bdd->prepare($updateSql);
        $stmt->bindParam(':completed', $isCompleted, PDO::PARAM_INT);
        $stmt->bindParam(':id_task', $taskId, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        echo "Erreur lors de la mise à jour : " . $e->getMessage();
    }
}

// Récup les tâches propres à l'id connecté
try {
    $sql = "SELECT task.id_task, task.name, task.description, task.due_date, task.completed 
            FROM `task`
            INNER JOIN `user_task` ON task.id_task = user_task.id_task
            WHERE user_task.id_user = :user_id
            ORDER BY task.due_date";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "An error occurred while getting the tasks : " . $e->getMessage();
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
<div class="grey">

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

</div>


<div class="todo">
<h2 class="green cerco size">TO DO</h2>
    <?php if ($isLoggedIn): ?>
        <a href="./add_task.php"><button type="submit" name="add" class="btn btn-info">Add a new task</button></a>
    <?php else: ?>
        <!-- Message si pas log-->
        <div class="alert alert-warning cerco-light" role="alert">
            You must have logged in to add a new task.
            <br>
        </div>
    <?php endif; ?>
</div>

<ul class="list-group flex">
    <?php foreach ($tasks as $task): ?>
        <li class="list-group-item">
            <div class="card" style="width: 18rem;">
                <div class="card-body">
                    <h5 class="card-title">
                        <?php echo htmlspecialchars($task['name']); ?>
                        <?php if (!empty($task['updated'])): ?>
                            <small class="text-muted">(updated)</small>
                        <?php endif; ?>
                    </h5>
                    <p class="card-text"><span class="brown-dot"></span><?php echo htmlspecialchars($task['description']); ?></p>
                    <p class="card-text">Date butoire: <?php echo htmlspecialchars($task['due_date']); ?></p>

                    <!-- la dixite checkbox tah les oufs -->
                    <form action="" method="POST" class="d-flex align-items-center">
                        <input type="hidden" name="task_id" value="<?php echo $task['id_task']; ?>">
                        <label for="completed-<?php echo $task['id_task']; ?>" class="form-check-label me-2 tb cerco-reg">
                            DONE
                        </label>
                        <input 
                            type="checkbox" 
                            class="form-check-input" 
                            id="completed-<?php echo $task['id_task']; ?>" 
                            name="completed" 
                            value="1" 
                            <?php echo $task['completed'] ? 'checked' : ''; ?> 
                            onchange="this.form.submit()">
                    </form>

                    <div class="right">
                        <a href="delete_task.php?task_id=<?php echo $task['id_task']; ?>" class="card-link"><img src="./asset/img/trash-01.svg" alt="picto poubelle"></a>
                        <a href="update_task.php?task_id=<?php echo $task['id_task']; ?>" class="card-link rotate"><img class="rotate" src="./asset/img/settings-01.svg" alt="picto settings"></a>
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
                    <button type="submit" name="done" class="btn btn-primary cerco-reg">DONE</button>
                    <div class="right">
                        <a href="delete_task.php?task_id=<?php echo $task['id_task']; ?>" class="card-link"><img src="./asset/img/trash-01.svg" alt="picto poubelle"></a>
                        <a href="update_task.php?task_id=<?php echo $task['id_task']; ?>" class="card-link"><img class="rotate" src="./asset/img/settings-01.svg" alt="picto settings"></a>
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
                        <p>TeamTasker is the optimum tool to manage and organize your tasks. We appreciate you using our services and we work everyday on making TeamTasker more attractive and useful !</p>
                    </div>
                    <div class="col item social">
                        <a href="https://blank.page/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M512 256C512 114.6 397.4 0 256 0S0 114.6 0 256C0 376 82.7 476.8 194.2 504.5V334.2H141.4V256h52.8V222.3c0-87.1 39.4-127.5 125-127.5c16.2 0 44.2 3.2 55.7 6.4V172c-6-.6-16.5-1-29.6-1c-42 0-58.2 15.9-58.2 57.2V256h83.6l-14.4 78.2H287V510.1C413.8 494.8 512 386.9 512 256h0z"/></svg></a>
                        <a href="https://blank.page/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg></a>
                        <a href="https://blank.page/"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--!Font Awesome Free 6.7.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.--><path d="M64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm297.1 84L257.3 234.6 379.4 396H283.8L209 298.1 123.3 396H75.8l111-126.9L69.7 116h98l67.7 89.5L313.6 116h47.5zM323.3 367.6L153.4 142.9H125.1L296.9 367.6h26.3z"/></svg></a>
                    </div>
                </div>
                <p class="copyright">TeamTasker © 2025</p>
            </div>
        </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  
</body>
</html>