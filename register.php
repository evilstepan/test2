<?php
// Включаем отображение ошибок
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Подключение к базе данных
$host = "localhost"; // Обычно это localhost
$user = "root"; // Укажите ваше имя пользователя MySQL
$password = ""; // Укажите ваш пароль MySQL
$dbname = "profsoyuz"; // Имя базы данных

// Создаем соединение
$conn = new mysqli($host, $user, $password, $dbname);

// Проверяем соединение
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error); // Ошибка подключения
} else {
    echo "Соединение успешно установлено.<br>"; // Для проверки
}

// Если данные формы отправлены
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = "Тест"; // Для теста
    $email = "test@example.com"; // Для теста
    $phone = "1234567890"; // Для теста
    $password = password_hash("testpass", PASSWORD_DEFAULT); // Хешируем пароль

    echo "Имя: $name, Email: $email, Телефон: $phone, Пароль: $password<br>"; // Вывод данных перед вставкой

    // Подготовка запроса
    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die("Ошибка подготовки запроса: " . $conn->error); // Вывод ошибки подготовки запроса
    }

    $stmt->bind_param("ssss", $name, $email, $phone, $password); // Привязка параметров

    // Выполнение запроса
    if ($stmt->execute()) {
        echo "Регистрация успешна!";
    } else {
        echo "Ошибка выполнения запроса: " . $stmt->error; // Вывод ошибки
    }

    $stmt->close(); // Закрываем подготовленный запрос
}

// Проверка данных в таблице
$result = $conn->query("SELECT * FROM users");
if ($result === false) {
    die("Ошибка выполнения запроса на выборку: " . $conn->error); // Проверка на ошибки выборки
}

if ($result->num_rows > 0) {
    echo "<h3>Данные в таблице 'users':</h3>";
    while ($row = $result->fetch_assoc()) {
        echo "Имя: " . $row['name'] . ", Email: " . $row['email'] . ", Телефон: " . $row['phone'] . "<br>";
    }
} else {
    echo "Таблица 'users' пуста.";
}

$conn->close(); // Закрываем соединение
?>