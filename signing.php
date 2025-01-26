<?php
session_start();
include_once('./connexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $pass = trim($_POST['pass']);

    $hash = password_hash($pass, PASSWORD_BCRYPT);

    try {
        $sql = "INSERT INTO `user` (`name`, `email`, `password`, `signing_date`) VALUES (:name, :email, :password, NOW())";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hash, PDO::PARAM_STR);
        $stmt->execute();

        

        header('Location: index.php');
        exit();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamTasker | Inscription</title>
    <meta name="description" content="Page d'inscription de TeamTasker">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./asset./css/style.css">
</head>
<body>

<div class="container mt-5">
    <div class="text-center mb-4">
        <img src="./asset\img\file-EAZkbufxqK3PMEF2xDQQDY 1.svg" alt="Logo TeamTasker" class="img-fluid" style="max-width: 150px;">
        <h2 class="green cerco size">SIGN IN</h2>
        <?php if ($alertMessage): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($alertMessage); ?>
            </div>
        <?php endif; ?>
    </div>
    <form action="" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="pass" class="form-label">Password</label>
            <input type="password" class="form-control" id="pass" name="pass" required>
        </div>
        <div class="mb-3">
            <label for="confirm_pass" class="form-label">Confirm password</label>
            <input type="password" class="form-control" id="confirm_pass" name="confirm_pass" required>
        </div>
        <button type="submit" class="btn btn-primary">Sign in !</button>
    </form>
    <div class="text-center mt-3">
        <a href="index.php" class="btn btn2 btn-secondary effect effect-3">Back to homepage</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  
</body>
</html>