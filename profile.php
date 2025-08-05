<?php
// Инициализация сессии
session_start();

// Проверяем авторизацию
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Подключение к базе данных
require_once 'db_config.php';

// Получаем ID пользователя из сессии
$user_id = $_SESSION['user_id'];

// Получаем данные пользователя из базы данных
$query = "SELECT nickname, level, admin, money, donate, house, bussiness, job, salary, fraction, rank, car FROM accounts WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    // Если пользователь не найден, разлогиниваем его
    session_destroy();
    header("Location: login.php?error=user_not_found");
    exit;
}

$user = mysqli_fetch_assoc($result);

// Получаем информацию о домах пользователя
$house_id = $user['house'];
$house_info = "Нет";

if ($house_id != -1) {
    $house_query = "SELECT house_price FROM house WHERE house_id = '$house_id'";
    $house_result = mysqli_query($conn, $house_query);
    
    if ($house_result && mysqli_num_rows($house_result) > 0) {
        $house = mysqli_fetch_assoc($house_result);
        $house_info = "#{$house_id} - " . number_format($house['house_price']) . "₽";
    }
}

// Получаем информацию о бизнесах пользователя
$business_id = $user['bussiness'];
$business_info = "Нет";

if ($business_id != -1) {
    $business_query = "SELECT buisnes_type, buisnes_name, buisnes_price FROM buisnes WHERE buisnes_id = '$business_id'";
    $business_result = mysqli_query($conn, $business_query);
    
    if ($business_result && mysqli_num_rows($business_result) > 0) {
        $business = mysqli_fetch_assoc($business_result);
        $business_type = $business['buisnes_type'];
        $business_name = $business['buisnes_name'];
        $business_price = number_format($business['buisnes_price']);
        
        $business_info = "#{$business_id} - {$business_price}₽";
    }
}

// Информация о работе пользователя
$job_id = $user['job'];
$job_info = "Безработный";
$salary_info = "0₽";

if ($job_id > 0) {
    // Названия работ
    $job_names = [
        1 => "Таксист",
        2 => "Дальнобойщик",
        3 => "Развозчик продуктов",
        4 => "Механик",
        5 => "Водитель автобуса",
        6 => "Разносчик газет",
        7 => "Строитель",
        8 => "Уборщик улиц",
        9 => "Разнорабочий",
        10 => "Работник фермы"
    ];
    
    $job_info = isset($job_names[$job_id]) ? $job_names[$job_id] : "Работа #{$job_id}";
    $salary_info = number_format($user['salary']) . "₽";
}

// Информация об организации пользователя
$fraction_id = $user['fraction'];
$fraction_info = "Нет";
$rank_info = "";

if ($fraction_id > 0) {
    // Названия фракций
    $fraction_names = [
        1 => "Полиция ЛС",
        2 => "ФБР",
        3 => "Армия",
        4 => "Медики",
        5 => "Правительство ЛС",
        6 => "Автошкола",
        7 => "Радио ЛС",
        8 => "Мафия"
    ];
    
    $fraction_info = isset($fraction_names[$fraction_id]) ? $fraction_names[$fraction_id] : "Организация #{$fraction_id}";
    $rank_info = "Ранг: " . $user['rank'];
}

// Получаем информацию о автомобилях пользователя
$car_info = "Нет";
$car_query = "SELECT id, model FROM player_vehicles WHERE owner = '{$user['nickname']}'";
$car_result = mysqli_query($conn, $car_query);

if ($car_result && mysqli_num_rows($car_result) > 0) {
    $cars = [];
    while ($car = mysqli_fetch_assoc($car_result)) {
        $cars[] = "#{$car['id']} (Модель: {$car['model']})";
    }
    $car_info = implode("<br>", $cars);
}

// Обработка выхода из аккаунта
if (isset($_GET['logout'])) {
    // Уничтожаем сессию
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет - Dream Mobile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Unbounded:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .stat-card {
            transform: translateY(20px);
            opacity: 0;
            animation: fadeUp 0.5s ease forwards;
        }
        
        @keyframes fadeUp {
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .stat-card:nth-child(1) { animation-delay: 0.1s; }
        .stat-card:nth-child(2) { animation-delay: 0.2s; }
        .stat-card:nth-child(3) { animation-delay: 0.3s; }
        .stat-card:nth-child(4) { animation-delay: 0.4s; }
        .stat-card:nth-child(5) { animation-delay: 0.5s; }
        .stat-card:nth-child(6) { animation-delay: 0.6s; }
        .stat-card:nth-child(7) { animation-delay: 0.7s; }
    </style>
</head>
<body class="bg-black text-white font-montserrat">
    <header class="fixed w-full py-4 z-50 transition-all duration-300" id="main-header">
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center">
                <a href="/" class="text-2xl font-bold font-unbounded bg-gradient-to-r from-blue-500 to-blue-300 text-transparent bg-clip-text">DREAM MOBILE</a>
                <nav class="hidden md:block">
                    <ul class="flex space-x-8">
                        <li><a href="/#features" class="text-gray-300 hover:text-white transition-colors duration-300">Особенности</a></li>
                        <li><a href="/#screenshots" class="text-gray-300 hover:text-white transition-colors duration-300">Скриншоты</a></li>
                        <li><a href="donate.php" class="text-gray-300 hover:text-white transition-colors duration-300">Донат</a></li>
                        <li><a href="profile.php?logout=1" class="bg-red-600 bg-opacity-10 border border-red-500 border-opacity-40 px-5 py-2 rounded-full hover:bg-opacity-20 transition-all duration-300">Выйти</a></li>
                    </ul>
                </nav>
                <button class="md:hidden text-gray-300 focus:outline-none menu-toggle">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </div>
        <!-- Mobile menu -->
        <div class="md:hidden mobile-menu hidden bg-black bg-opacity-95 absolute w-full left-0 py-4 px-6 border-t border-gray-800 mt-4 animate-fadeIn">
            <ul class="space-y-4">
                <li><a href="/#features" class="block text-gray-300 hover:text-white transition-colors duration-300">Особенности</a></li>
                <li><a href="/#screenshots" class="block text-gray-300 hover:text-white transition-colors duration-300">Скриншоты</a></li>
                <li><a href="donate.php" class="block text-gray-300 hover:text-white transition-colors duration-300">Донат</a></li>
                <li><a href="profile.php?logout=1" class="block bg-red-600 bg-opacity-10 border border-red-500 border-opacity-40 px-5 py-2 rounded-full hover:bg-opacity-20 transition-all duration-300 w-full text-center mt-4">Выйти</a></li>
            </ul>
        </div>
    </header>

    <main>
        <!-- Profile Section -->
        <section class="pt-40 pb-24 relative">
            <div class="absolute inset-0 z-0 bg-gradient-radial"></div>
            <div class="container mx-auto px-6">
                <div class="max-w-4xl mx-auto relative z-10">
                    <div class="flex flex-col items-center mb-12">
                        <div class="w-24 h-24 bg-blue-600 bg-opacity-10 border-2 border-blue-500 border-opacity-40 rounded-full flex items-center justify-center mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h1 class="text-4xl md:text-5xl font-bold font-unbounded mb-4 text-center">
                            <span class="animate-gradient-text"><?php echo htmlspecialchars($user['nickname']); ?></span>
                        </h1>
                        <p class="text-lg text-gray-400 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 5a3 3 0 015-2.236A3 3 0 0114.83 6H16a2 2 0 110 4h-5V9a1 1 0 10-2 0v1H4a2 2 0 110-4h1.17C5.06 5.687 5 5.35 5 5zm4 1V5a1 1 0 10-1 1h1zm3 0a1 1 0 10-1-1v1h1z" clip-rule="evenodd" />
                                <path d="M9 11H3v5a2 2 0 002 2h4v-7zM11 18h4a2 2 0 002-2v-5h-6v7z" />
                            </svg>
                            Уровень: <span class="text-white ml-1"><?php echo $user['level']; ?></span>
                        </p>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                        <div class="backdrop-blur-sm bg-white/5 rounded-2xl p-6 border border-white/10 stat-card">
                            <div class="flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                                </svg>
                                <h3 class="text-lg font-medium">Баланс</h3>
                            </div>
                            <p class="text-3xl font-bold font-unbounded text-green-400">
                                <?php echo number_format($user['money']); ?> ₽
                            </p>
                        </div>
                        
                        <div class="backdrop-blur-sm bg-white/5 rounded-2xl p-6 border border-white/10 stat-card">
                            <div class="flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-purple-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 2a2 2 0 00-2 2v14l3.5-2 3.5 2 3.5-2 3.5 2V4a2 2 0 00-2-2H5zm2.5 3a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm6 4a1.5 1.5 0 100 3 1.5 1.5 0 000-3z" clip-rule="evenodd" />
                                </svg>
                                <h3 class="text-lg font-medium">Донат</h3>
                            </div>
                            <p class="text-3xl font-bold font-unbounded text-purple-400">
                                <?php echo number_format($user['donate']); ?> DC
                            </p>
                        </div>
                        
                        <div class="backdrop-blur-sm bg-white/5 rounded-2xl p-6 border border-white/10 stat-card">
                            <div class="flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 005 10a6 6 0 0012 0c0-.552-.115-1.078-.321-1.554A5.001 5.001 0 0010 11z" clip-rule="evenodd" />
                                </svg>
                                <h3 class="text-lg font-medium">Статус</h3>
                            </div>
                            <p class="text-2xl font-bold font-unbounded <?php echo $user['admin'] > 0 ? 'text-red-400' : 'text-gray-400'; ?>">
                                <?php echo $user['admin'] > 0 ? 'Администратор' : 'Игрок'; ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <div class="backdrop-blur-sm bg-white/5 rounded-2xl p-6 border border-white/10 stat-card">
                            <div class="flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                                </svg>
                                <h3 class="text-lg font-medium">Дом</h3>
                            </div>
                            <p class="text-xl font-bold font-unbounded text-blue-400">
                                <?php echo $house_info; ?>
                            </p>
                        </div>
                        
                        <div class="backdrop-blur-sm bg-white/5 rounded-2xl p-6 border border-white/10 stat-card">
                            <div class="flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd" />
                                </svg>
                                <h3 class="text-lg font-medium">Бизнес</h3>
                            </div>
                            <p class="text-xl font-bold font-unbounded text-orange-400">
                                <?php echo $business_info; ?>
                            </p>
                        </div>
                        
                        <div class="backdrop-blur-sm bg-white/5 rounded-2xl p-6 border border-white/10 stat-card">
                            <div class="flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd" />
                                    <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z" />
                                </svg>
                                <h3 class="text-lg font-medium">Работа</h3>
                            </div>
                            <div>
                                <p class="text-xl font-bold font-unbounded text-teal-400">
                                    <?php echo $job_info; ?>
                                </p>
                                <p class="text-sm text-gray-400 mt-2">
                                    Зарплата: <span class="text-teal-400 font-semibold"><?php echo $salary_info; ?></span>
                                </p>
                            </div>
                        </div>
                        
                        <div class="backdrop-blur-sm bg-white/5 rounded-2xl p-6 border border-white/10 stat-card">
                            <div class="flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                                </svg>
                                <h3 class="text-lg font-medium">Организация</h3>
                            </div>
                            <div>
                                <p class="text-xl font-bold font-unbounded text-indigo-400">
                                    <?php echo $fraction_info; ?>
                                </p>
                                <?php if (!empty($rank_info)): ?>
                                <p class="text-sm text-gray-400 mt-2">
                                    <?php echo $rank_info; ?>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="backdrop-blur-sm bg-white/5 rounded-2xl p-6 border border-white/10 stat-card">
                            <div class="flex items-center mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H11a1 1 0 001-1v-1h3.05a2.5 2.5 0 014.9 0H19a1 1 0 001-1v-4a1 1 0 00-.293-.707L17 5.586V4a1 1 0 00-1-1H3z" />
                                </svg>
                                <h3 class="text-lg font-medium">Автомобили</h3>
                            </div>
                            <div>
                                <p class="text-md font-semibold text-red-400">
                                    <?php echo $car_info; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="py-12 bg-black border-t border-gray-900">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <a href="/" class="text-2xl font-bold font-unbounded bg-gradient-to-r from-blue-500 to-blue-300 text-transparent bg-clip-text mb-6 md:mb-0">DREAM MOBILE</a>
                <div class="flex space-x-6">
                    <a href="privacy-policy.php" class="text-gray-500 hover:text-gray-300 transition-colors">Политика конфиденциальности</a>
                    <a href="terms.php" class="text-gray-500 hover:text-gray-300 transition-colors">Оферта</a>
                </div>
                <div class="text-gray-500 text-sm mt-6 md:mt-0">© 2025 Dream Mobile. Все права защищены.</div>
            </div>
        </div>
    </footer>   

    <script src="js/main.js"></script>
</body>
</html> 