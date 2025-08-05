<?php
// Обработчик уведомлений об оплате от AAIO

// Логирование запросов для отладки
$log_file = 'payment_logs.txt';
file_put_contents($log_file, date('Y-m-d H:i:s') . " - Received callback: " . print_r($_POST, true) . "\n", FILE_APPEND);

// Проверяем, что получены все необходимые параметры
if (!isset($_POST['order_id']) || !isset($_POST['merchant_id']) || !isset($_POST['amount']) || !isset($_POST['currency']) || !isset($_POST['sign'])) {
    http_response_code(400);
    echo "ERROR: Missing required parameters";
    exit;
}

// Получаем данные из запроса
$merchant_id = $_POST['merchant_id'];
$amount = $_POST['amount'];
$currency = $_POST['currency'];
$order_id = $_POST['order_id'];
$sign = $_POST['sign'];

// Настройки магазина
$merchant_id_expected = 'c47408be-9fe6-4b7b-95a9-a2c488f17d73';
$secret_key_2 = '6bb2875f5f9633bed9b40556cd548ff9'; // Секретный ключ №2

// Проверяем идентификатор магазина
if ($merchant_id !== $merchant_id_expected) {
    http_response_code(400);
    echo "ERROR: Invalid merchant ID";
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - Invalid merchant ID\n", FILE_APPEND);
    exit;
}

// Формируем нашу подпись для проверки
$sign_data = implode(':', [$merchant_id, $amount, $currency, $secret_key_2, $order_id]);
$sign_check = hash('sha256', $sign_data);

// Проверяем подпись
if ($sign !== $sign_check) {
    http_response_code(400);
    echo "ERROR: Invalid signature";
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - Invalid signature\n", FILE_APPEND);
    exit;
}

// Проверяем статус платежа
if (!isset($_POST['status']) || $_POST['status'] !== 'success') {
    http_response_code(200); // Все равно возвращаем 200, так как запрос корректный
    echo "WAIT";
    file_put_contents($log_file, date('Y-m-d H:i:s') . " - Payment status is not success: {$_POST['status']}\n", FILE_APPEND);
    exit;
}

// Извлекаем никнейм
$nickname = isset($_POST['us_nickname']) ? $_POST['us_nickname'] : 'Unknown';

// Обрабатываем успешный платеж
// Здесь должна быть логика начисления доната пользователю
// Например, сохранение в базу данных, обновление статуса и т.д.

file_put_contents($log_file, date('Y-m-d H:i:s') . " - Payment successful for order {$order_id}, nickname: {$nickname}, amount: {$amount} {$currency}\n", FILE_APPEND);

// Отправляем успешный ответ системе AAIO
echo "OK";
exit;
?> 