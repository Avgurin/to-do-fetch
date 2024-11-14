<?php
require_once '../db.php';

session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Генерация токена
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToDo Example</title>
</head>
<body>
    <form id="myForm">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="text" name="name" placeholder="Enter to-do name">
        <input type="text" name="body" placeholder="Enter to-do body">
        <button type="submit">Add new</button>
    </form>
    <div class="response" id="response">
    </div>

    <script>
        const responseTextInput = document.getElementById('response');

        document.getElementById('myForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            fetch('/add.php', {
                method: 'POST',
                body: formData
                })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Сеть ответила с ошибкой: ' + response.status);
                    }
                return response.json(); // response.json() / response.text()
                })
            .then(data => {
                //responseTextInput.innerHTML = JSON.stringify(data); // Обработка ответа  (JSON.stringify(data))
                responseTextInput.innerHTML = data.map(item => `<li>${item.name} ${item.body}</li>`).join('');
            })
            .catch(error => {
                console.error('Ошибка:', error);
            });

        });
    </script>
</body>
</html>