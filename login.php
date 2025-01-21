<?php
session_start();
include_once('./connexion.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TeamTasker |Login</title>
    <meta name="description" content="Page de connexion de TeamTasker">
    <link rel="stylesheet" href="./asset./css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
 
</head>
<body>
    
<form action="./login.php" method="POST">

        <label for="name">Nom/Mail: </label>
        <input type="text" name="name" id="name">

        <label for="password">MotDePasse: </label>
        <input type="password" name="pass" id="password">

        <label for="remember">Se souvenir de moi</label>
        <input type="checkbox" name="remember" id="remember">

        <input type="submit" value="Je me connecte">
</form><br>
<p>Pas encore de compte ?</p><br>
<a href="./signing.php">Je m'inscris</a>

<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pseudo = isset($_POST['name']) ? trim($_POST['name']) : '';
    $pass = isset($_POST['pass']) ? trim($_POST['pass']) : '';

    if (!empty($pseudo) && !empty($pass)) {
        $req = $bdd->prepare('SELECT `id_user`, `name`, `email`, `password` FROM `user` WHERE `name` = :pseudo OR `email` = :pseudo');
        $req->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $req->execute();

        $user = $req->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['username'] = $user['name'];

            header('Location: index.php');
            exit();
        } else {
            echo 'Nom d\'utilisateur ou mot de passe incorrect';
        }
    } else {
        echo 'Veuillez remplir tous les champs.';
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  
</body>
</html>