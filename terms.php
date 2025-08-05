<?php
// Инициализация сессии
session_start();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Публичная оферта - Dream Mobile</title>
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
        <section class="pt-40 pb-20">
            <div class="container mx-auto px-6">
                <h1 class="text-3xl md:text-4xl font-bold font-unbounded mb-10 text-center">Публичная оферта</h1>
                
                <div class="max-w-4xl mx-auto bg-white/5 rounded-2xl p-8 backdrop-blur-sm">
                    <div class="mb-8">
                        <p class="text-gray-300 mb-4">Дата последнего обновления: 16 июня 2025</p>
                        
                        <p class="text-gray-300 mb-4">
                            Настоящий документ является публичной офертой (далее — «Оферта») физического лица Ельсуков Семен, (далее — «Разработчик») и содержит все существенные условия использования игры "Dream Mobile CRMP" (далее — «Игра») и заключения лицензионного соглашения на использование Игры.
                        </p>
                        
                        <p class="text-gray-300 mb-4">
                            В соответствии с пунктом 2 статьи 437 Гражданского Кодекса Российской Федерации (ГК РФ), в случае принятия изложенных ниже условий и оплаты услуг, юридическое или физическое лицо, производящее акцепт этой Оферты, становится Пользователем (в соответствии с пунктом 3 статьи 438 ГК РФ акцепт Оферты равносилен заключению договора на условиях, изложенных в Оферте).
                        </p>
                        
                        <p class="text-gray-300 mb-4">
                            Внимательно прочитайте условия настоящей Оферты, и, если Вы не согласны с каким-либо из её положений, Разработчик предлагает Вам отказаться от использования Игры.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-xl font-bold font-unbounded mb-4">1. Термины и определения</h2>
                        <p class="text-gray-300 mb-4">
                            1.1. «Игра» — многопользовательская игра "Dream Mobile CRMP", разработанная и принадлежащая Разработчику, включая все её элементы и программный код.
                        </p>
                        <p class="text-gray-300 mb-4">
                            1.2. «Пользователь» — физическое лицо, достигшее 18 лет или несовершеннолетнее лицо с согласия своих законных представителей, которое приняло условия настоящей Оферты.
                        </p>
                        <p class="text-gray-300 mb-4">
                            1.3. «Аккаунт» — учетная запись Пользователя, создаваемая в момент регистрации в Игре, позволяющая Разработчику идентифицировать Пользователя.
                        </p>
                        <p class="text-gray-300 mb-4">
                            1.4. «Внутриигровая валюта» — условные единицы, которые Пользователь может приобретать за реальные деньги и использовать для оплаты Внутриигровых покупок.
                        </p>
                        <p class="text-gray-300 mb-4">
                            1.5. «Внутриигровые покупки» — виртуальные предметы, услуги или возможности, предоставляемые Пользователю в Игре.
                        </p>
                    </div>

                    <!-- Остальное содержимое документа -->
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