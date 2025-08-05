<?php
// Инициализация сессии
session_start();

// Подключение к базе данных
require_once 'db_config.php';

// Получаем топ-10 самых богатых игроков
$query = "SELECT nickname, level, money FROM accounts ORDER BY money DESC LIMIT 10";
$result = mysqli_query($conn, $query);

// Проверка результата запроса
if (!$result) {
    die("Ошибка запроса к базе данных: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Самые богатые игроки - Dream Mobile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Unbounded:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .top-player {
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
        
        .leader-card {
            position: relative;
            overflow: hidden;
        }
        
        .leader-card::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at center, rgba(59, 130, 246, 0.3) 0%, transparent 70%);
            z-index: -1;
        }
        
        .crown-gold {
            color: #FFD700;
            filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.5));
        }
        
        .crown-silver {
            color: #C0C0C0;
            filter: drop-shadow(0 0 5px rgba(192, 192, 192, 0.5));
        }
        
        .crown-bronze {
            color: #CD7F32;
            filter: drop-shadow(0 0 5px rgba(205, 127, 50, 0.5));
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }
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
                        <li><a href="richest.php" class="text-gray-300 hover:text-white transition-colors duration-300">Топ игроков</a></li>
                        <li><a href="donate.php" class="text-gray-300 hover:text-white transition-colors duration-300">Донат</a></li>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <li><a href="profile.php" class="bg-blue-600 bg-opacity-10 border border-blue-500 border-opacity-40 px-5 py-2 rounded-full hover:bg-opacity-20 transition-all duration-300">Личный кабинет</a></li>
                        <?php else: ?>
                            <li><a href="login.php" class="bg-blue-600 bg-opacity-10 border border-blue-500 border-opacity-40 px-5 py-2 rounded-full hover:bg-opacity-20 transition-all duration-300">Войти</a></li>
                        <?php endif; ?>
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
                <li><a href="richest.php" class="block text-gray-300 hover:text-white transition-colors duration-300">Топ игроков</a></li>
                <li><a href="donate.php" class="block text-gray-300 hover:text-white transition-colors duration-300">Донат</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="profile.php" class="block bg-blue-600 bg-opacity-10 border border-blue-500 border-opacity-40 px-5 py-2 rounded-full hover:bg-opacity-20 transition-all duration-300 w-full text-center mt-4">Личный кабинет</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="block bg-blue-600 bg-opacity-10 border border-blue-500 border-opacity-40 px-5 py-2 rounded-full hover:bg-opacity-20 transition-all duration-300 w-full text-center mt-4">Войти</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <main>
        <!-- Richest Players Section -->
        <section class="pt-40 pb-24 relative">
            <div class="absolute inset-0 z-0 bg-gradient-radial"></div>
            <div class="container mx-auto px-6 relative z-10">
                <h1 class="text-4xl md:text-5xl font-bold font-unbounded mb-20 text-center animate-on-scroll opacity-0">
                    <span class="bg-gradient-to-r from-blue-500 to-blue-300 text-transparent bg-clip-text">Самые богатые игроки</span>
                </h1>
                
                <!-- Top 3 Players -->
                <div class="mb-24">
                    <h2 class="text-3xl font-bold font-unbounded mb-16 text-center">
                        <span class="text-yellow-400">Топ 3 игрока</span>
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <?php
                        $top_players = [];
                        $i = 0;
                        
                        // Get top 3 players
                        while ($row = mysqli_fetch_assoc($result)) {
                            if ($i < 3) {
                                $top_players[] = $row;
                            }
                            $i++;
                        }
                        
                        // Display top 3 players
                        foreach ($top_players as $index => $player) {
                            $position = $index + 1;
                            $crownClass = "";
                            $bgClass = "";
                            $pulseClass = "";
                            
                            switch ($position) {
                                case 1:
                                    $crownClass = "crown-gold";
                                    $bgClass = "from-yellow-700 to-yellow-500";
                                    $pulseClass = "pulse";
                                    break;
                                case 2:
                                    $crownClass = "crown-silver";
                                    $bgClass = "from-slate-500 to-slate-400";
                                    break;
                                case 3:
                                    $crownClass = "crown-bronze";
                                    $bgClass = "from-amber-800 to-amber-600";
                                    break;
                            }
                            
                            echo '<div class="backdrop-blur-sm bg-white/5 rounded-2xl p-8 border border-white/10 leader-card top-player" style="animation-delay: ' . ($index * 0.2) . 's">';
                            echo '<div class="absolute top-0 right-0 left-0 h-1 bg-gradient-to-r ' . $bgClass . ' ' . $pulseClass . '"></div>';
                            echo '<div class="flex flex-col items-center">';
                            
                            // Crown for top 3
                            echo '<div class="text-4xl mb-4 ' . $crownClass . '">';
                            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="currentColor" viewBox="0 0 16 16">';
                            echo '<path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>';
                            echo '<path d="M4.285 12.433a.5.5 0 0 0 .683-.183A3.498 3.498 0 0 1 8 10.5c1.295 0 2.426.703 3.032 1.75a.5.5 0 0 0 .866-.5A4.498 4.498 0 0 0 8 9.5a4.5 4.5 0 0 0-3.898 2.25.5.5 0 0 0 .183.683zm6.991-8.38a.5.5 0 1 1 .448.894l-1.009.504c.176.27.285.64.285 1.049 0 .828-.448 1.5-1 1.5s-1-.672-1-1.5c0-.247.04-.48.11-.686a.502.502 0 0 1 .166-.761l2-1zm-6.552 0a.5.5 0 0 0-.448.894l1.009.504A1.94 1.94 0 0 0 5 6.5C5 7.328 5.448 8 6 8s1-.672 1-1.5c0-.247-.04-.48-.11-.686a.502.502 0 0 0-.166-.761l-2-1z"/>';
                            echo '</svg>';
                            echo '</div>';
                            
                            echo '<h3 class="text-2xl font-bold mb-2">' . htmlspecialchars($player['nickname']) . '</h3>';
                            echo '<p class="text-gray-400 mb-4">Уровень: ' . $player['level'] . '</p>';
                            echo '<p class="text-3xl font-bold font-unbounded text-green-400">' . number_format($player['money']) . ' ₽</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                        
                        // Reset pointer to beginning
                        mysqli_data_seek($result, 0);
                        ?>
                    </div>
                </div>
                
                <!-- Remaining Players in Top 10 -->
                <div>
                    <h2 class="text-2xl font-bold font-unbounded mb-10 text-center">
                        <span class="text-blue-400">Остальные игроки из топ-10</span>
                    </h2>
                    
                    <div class="backdrop-blur-sm bg-white/5 rounded-2xl p-6 border border-white/10 top-player" style="animation-delay: 0.8s">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-700">
                                    <th class="py-4 text-left">Место</th>
                                    <th class="py-4 text-left">Никнейм</th>
                                    <th class="py-4 text-left">Уровень</th>
                                    <th class="py-4 text-left">Баланс</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $i = 0;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $i++;
                                    if ($i > 3) {
                                        echo '<tr class="border-b border-gray-800 hover:bg-white/5 transition-all">';
                                        echo '<td class="py-4">' . ($i) . '</td>';
                                        echo '<td class="py-4 font-semibold">' . htmlspecialchars($row['nickname']) . '</td>';
                                        echo '<td class="py-4">' . $row['level'] . '</td>';
                                        echo '<td class="py-4 font-semibold text-green-400">' . number_format($row['money']) . ' ₽</td>';
                                        echo '</tr>';
                                    }
                                }
                                
                                // If there are less than 10 players, fill with empty rows
                                for ($j = $i; $j < 10; $j++) {
                                    echo '<tr class="border-b border-gray-800">';
                                    echo '<td class="py-4">' . ($j + 1) . '</td>';
                                    echo '<td class="py-4 font-semibold text-gray-600">—</td>';
                                    echo '<td class="py-4 text-gray-600">—</td>';
                                    echo '<td class="py-4 font-semibold text-gray-600">—</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
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