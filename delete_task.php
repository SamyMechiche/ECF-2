<?php
session_start();
include_once('./connexion.php');

if (isset($_GET['task_id'])) {
    $task_id = $_GET['task_id'];

    try {
        $sql = "DELETE FROM `task` WHERE `id_task` = :task_id";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
        $stmt->execute();

        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de la suppression de la tâche : " . $e->getMessage();
    }
} else {
    echo "ID de la tâche non fourni.";
}
?>
