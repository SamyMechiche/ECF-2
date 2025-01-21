<?php
include_once('./connexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $name = ($_POST['name']);
    $email = ($_POST['email']);
    $pass = ($_POST['password']);

    $hash = password_hash($pass, PASSWORD_BCRYPT);

    try {
        $sql = "INSERT INTO `user` (`name`, `email`, `password`, `signing_date`) VALUES ( :name, :email, :password, NOW())";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam('name', $name, PDO::PARAM_STR);
        $stmt->bindParam('email', $email, PDO::PARAM_STR);
        $stmt->bindParam('password', $hash, PDO::PARAM_STR);
        $stmt->execute();
        
        header('Location: index.php');

    } catch (PDOException $e){
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
    <link rel="stylesheet" href="./asset./css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>

<form action="./signing.php" method="POST">

        <label for="name">Nom: </label>
        <input type="text" name="name" id="name">
        
        <label for="email">Mail: </label>
        <input type="email" name="email" id="email">

        <label for="password">MotDePasse: </label>
        <input type="password" name="password" id="password">

        <input type="submit" value="Je m'inscris">
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  
</body>
</html>