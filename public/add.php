<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        echo "Ошибка: недопустимый токен";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO todos (name, body)
                VALUES (:name, :body)");
                $stmt->execute([
                    'name' => $_POST['name'],
                    'body' => $_POST['body'],
                    ]);
                } catch (PDOException $e) {
                    echo "Ошибка: " . $e->getMessage();
                }

                $stmt = $pdo->query('SELECT * FROM todos');
                $todos = $stmt->fetchAll();
                header('Content-Type: application/json');
                echo json_encode($todos);
                //echo json_encode($todos,JSON_PRETTY_PRINT);

                $pdo = null;
                //echo "успешно";
        }
} else {
    echo "Неверный метод запроса.";
}
?>