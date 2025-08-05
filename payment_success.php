<?php
// Инициализация сессии
session_start();

// Get payment ID from URL
$payment_id = isset($_GET['id']) ? $_GET['id'] : '';

if (empty($payment_id)) {
    header('Location: donate.php');
    exit;
}

// In a real implementation, here you would:
// 1. Connect to the database
// 2. Verify that the payment exists and is successful
// 3. Show appropriate information

// For demonstration purposes, we'll assume the payment was successful
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оплата успешна - Dream Mobile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Unbounded:wght@400;500;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
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
        <!-- Success Section -->
        <section class="pt-40 pb-24 relative">
            <div class="absolute inset-0 z-0 bg-gradient-radial"></div>
            <div class="container mx-auto px-6">
                <div class="max-w-2xl mx-auto relative z-10 text-center">
                    <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-8">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl font-bold font-unbounded mb-8">
                        Оплата успешна!
                    </h1>
                    
                    <p class="text-lg mb-8 text-gray-300">
                        Спасибо за поддержку проекта Dream Mobile! Ваш платеж успешно обработан.
                    </p>
                    
                    <div class="bg-white/5 rounded-3xl p-6 mb-10 backdrop-blur-sm border border-white/10">
                        <p class="text-gray-400">ID платежа: <span class="text-white"><?php echo htmlspecialchars($payment_id); ?></span></p>
                    </div>
                    
                    <div class="flex flex-col md:flex-row gap-4 justify-center">
                        <a href="/" class="bg-blue-600 hover:bg-blue-700 px-8 py-4 rounded-full text-lg font-bold transition-all duration-500 transform hover:scale-105">
                            На главную
                        </a>
                        <a href="donate" class="bg-transparent border border-blue-500 px-8 py-4 rounded-full text-lg font-bold transition-all duration-500 transform hover:scale-105">
                            Сделать еще донат
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="py-12 bg-black border-t border-gray-900">
        <div class="container mx-auto px-6">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <a href="/" class="text-2xl font-bold font-unbounded bg-gradient-to-r from-blue-500 to-blue-300 text-transparent bg-clip-text mb-6 md:mb-0">DREAM MOBILE</a>
                <div class="text-gray-500 text-sm">© 2025 Dream Mobile. Все права защищены.</div>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html> 