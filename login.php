<?php
session_start();
include_once('./connexion.php');

$alertMessage = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = isset($_POST['login']) ? trim($_POST['login']) : '';
    $pass = isset($_POST['pass']) ? trim($_POST['pass']) : '';

    if (!empty($login) && !empty($pass)) {
        try {
            // Vérifier les informations de connexion
            $req = $bdd->prepare('SELECT `id_user`, `name`, `email`, `password` FROM `user` WHERE `name` = :login OR `email` = :login');
            $req->bindParam(':login', $login, PDO::PARAM_STR);
            $req->execute();

            $user = $req->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($pass, $user['password'])) {
                $_SESSION['user_id'] = $user['id_user'];
                $_SESSION['username'] = $user['name'];

                header('Location: index.php');
                exit();
            } else {
                $alertMessage = 'Nom d\'utilisateur ou mot de passe incorrect.';
            }
        } catch (PDOException $e) {
            $alertMessage = "Erreur lors de la connexion : " . $e->getMessage();
        }
    } else {
        $alertMessage = 'Veuillez remplir tous les champs.';
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
</head>
<body>
    
<div class="container mt-5">
    <div class="text-center mb-4">
        <img src="./asset\img\file-EAZkbufxqK3PMEF2xDQQDY 1.svg" alt="Logo TeamTasker" class="img-fluid" style="max-width: 200px;">
        <h2 class="green cerco">SIGN IN</h2>
        <?php if ($alertMessage): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($alertMessage); ?>
            </div>
        <?php endif; ?>
    </div>
    <form action="" method="POST">
        <div class="mb-3">
            <label for="login" class="form-label">Email ou Nom d'utilisateur</label>
            <input type="text" class="form-control" id="login" name="login" required>
        </div>
        <div class="mb-3">
            <label for="pass" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="pass" name="pass" required>
        </div>
        <button type="submit" class="btn btn2 btn-primary">Connexion</button>
    </form>
    <div class="text-center mt-3">
        <a href="index.php" class="btn btn2 btn-secondary">Retour à l'accueil</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>  
</body>
</html>
