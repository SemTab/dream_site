<?php
// Получаем данные из формы
$nickname = isset($_POST['nickname']) ? trim($_POST['nickname']) : '';
$amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0;
$payment_id = 'DM' . time() . rand(1000, 9999);

// Проверяем входные данные
if (empty($nickname) || $amount < 10) {
    header('Location: donate.php?error=invalid_input');
    exit;
}

// Интеграция с платежной системой AAIO
$merchant_id = 'c47408be-9fe6-4b7b-95a9-a2c488f17d73'; // ID магазина
$currency = 'RUB'; // Валюта заказа
$secret = '40b08ef072ffa7e52e525939eab34d93'; // Секретный ключ №1
$order_id = $payment_id; // Идентификатор заказа в нашей системе
$sign = hash('sha256', implode(':', [$merchant_id, $amount, $currency, $secret, $order_id]));
$desc = 'Донат для Dream Mobile - ' . $nickname; // Описание заказа
$lang = 'ru'; // Язык формы

// Сохраняем информацию о платеже в сессии
session_start();
$_SESSION['payment_data'] = [
    'nickname' => $nickname,
    'amount' => $amount,
    'payment_id' => $payment_id
];

// Отправляем запрос на AAIO для получения URL оплаты
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://aaio.so/merchant/get_pay_url');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Accept: application/json',
    'Content-Type: application/x-www-form-urlencoded'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'merchant_id' => $merchant_id,
    'amount' => $amount,
    'currency' => $currency,
    'order_id' => $order_id,
    'sign' => $sign,
    'desc' => $desc,
    'lang' => $lang,
    'us_nickname' => $nickname
]));
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15); // Таймаут подключения
curl_setopt($ch, CURLOPT_TIMEOUT, 60); // Таймаут обработки запроса

$result = curl_exec($ch); // Получаем ответ
$http_code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE); // Код ответа

if (curl_errno($ch)) {
    // Ошибка соединения
    header('Location: donate.php?error=connection_error');
    exit;
}
curl_close($ch);

if(!in_array($http_code, [200, 400, 401])) {
    // Неизвестный код ответа
    header('Location: donate.php?error=unknown_response');
    exit;
}

$decoded = json_decode($result, true); // Парсим ответ

if(json_last_error() !== JSON_ERROR_NONE) {
    // Ошибка парсинга JSON
    header('Location: donate.php?error=invalid_response');
    exit;
}

if($decoded['type'] == 'success' && isset($decoded['url'])) {
    // Перенаправляем пользователя на страницу оплаты
    header('Location: ' . $decoded['url']);
} else {
    // Ошибка в ответе от платежной системы
    header('Location: donate.php?error=payment_error');
}
exit;
?> 