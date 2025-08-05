<?php
// Конфигурация подключения к базе данных
$db_host = '51.91.215.125'; // Хост
$db_user = 'gs279471'; // Имя пользователя
$db_pass = 'da12345'; // Пароль
$db_name = 'gs279471'; // Имя базы данных

// Создаем соединение
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Проверка соединения
if (!$conn) {
    die("Ошибка подключения к базе данных: " . mysqli_connect_error());
}

// Устанавливаем кодировку
mysqli_set_charset($conn, "utf8");
?> 