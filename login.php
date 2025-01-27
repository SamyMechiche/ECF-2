<?php
session_start(); // Démarrage de la grand-mère à la session
include_once('./connexion.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pseudo = isset($_POST['name']) ? trim($_POST['name']) : '';
    $pass = isset($_POST['pass']) ? trim($_POST['pass']) : '';

    // Différent de vide 
    if (!empty($pseudo) && !empty($pass)) {
        $req = $bdd->prepare('SELECT `id_user`, `name`, `email`, `password` FROM `user` WHERE `name` = :pseudo OR `email` = :pseudo');
        $req->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $req->execute();
        
        // Fetch les infos de user
        $user = $req->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['name'];

            header('Location: index.php');
            exit();
        } else {
            echo 'Wrong username or password.';
        }
    } else {
        echo 'Please fill all the fields.';
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamTasker | Connexion</title>
    <meta name="description" content="Page de connexion de TeamTasker">
    <link rel="stylesheet" href="./asset./css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="shortcut icon" href="asset\img\file-EAZkbufxqK3PMEF2xDQQDY 1.svg" type="image/x-icon">
</head>
<body>
    
<div class="container mt-5">
    <div class="text-center mb-4">
        <img src="./asset\img\file-EAZkbufxqK3PMEF2xDQQDY 1.svg" alt="Logo TeamTasker" class="img-fluid" style="max-width: 200px;">
        <h2 class="green cerco">LOGIN</h2>

    </div>
    <form action="" method="POST">
        <div class="mb-3">
            <label for="login" class="form-label">Mail or Username</label>
            <input type="text" class="form-control" id="login" name="name" required>
        </div>
        <div class="mb-3">
            <label for="pass" class="form-label">Password</label>
            <input type="password" class="form-control" id="pass" name="pass" required>
        </div>
        <button type="submit" class="btn btn2 btn-primary">Login</button>
    </form>

    <div class="text-center mt-3">
        <a href="signing.php" class="btn btn2 btn-success">Create an account</a>
        <a href="index.php" class="btn btn2 btn-secondary">Back to homepage</a>
    </div>
    
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  
</body>
</html>