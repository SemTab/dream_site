<?php
// Инициализация сессии
session_start();

// Обработка ошибок оплаты
$error_message = '';
if (isset($_GET['error'])) {
    switch ($_GET['error']) {
        case 'invalid_input':
            $error_message = 'Проверьте правильность введенных данных. Сумма должна быть не менее 10 руб.';
            break;
        case 'connection_error':
            $error_message = 'Ошибка подключения к платежной системе. Пожалуйста, попробуйте позже.';
            break;
        case 'unknown_response':
            $error_message = 'Неизвестная ошибка платежной системы. Пожалуйста, попробуйте позже.';
            break;
        case 'invalid_response':
            $error_message = 'Ошибка обработки ответа от платежной системы. Пожалуйста, попробуйте позже.';
            break;
        case 'payment_error':
            $error_message = 'Ошибка при создании платежа. Пожалуйста, попробуйте позже.';
            break;
        default:
            $error_message = 'Произошла ошибка. Пожалуйста, попробуйте позже.';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Донат - Dream Mobile</title>
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
                        <li><a href="donate.php" class="bg-blue-600 bg-opacity-10 border border-blue-500 border-opacity-40 px-5 py-2 rounded-full hover:bg-opacity-20 transition-all duration-300">Донат</a></li>
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <li><a href="profile.php" class="text-gray-300 hover:text-white transition-colors duration-300">Личный кабинет</a></li>
                        <?php else: ?>
                            <li><a href="login.php" class="text-gray-300 hover:text-white transition-colors duration-300">Войти</a></li>
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
                <li><a href="donate.php" class="block bg-blue-600 bg-opacity-10 border border-blue-500 border-opacity-40 px-5 py-2 rounded-full hover:bg-opacity-20 transition-all duration-300 w-full text-center mt-4">Донат</a></li>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <li><a href="profile.php" class="block text-gray-300 hover:text-white transition-colors duration-300">Личный кабинет</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="block text-gray-300 hover:text-white transition-colors duration-300">Войти</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <main>
        <!-- Donation Section -->
        <section class="pt-40 pb-24 relative">
            <div class="absolute inset-0 z-0 bg-gradient-radial"></div>
            <div class="container mx-auto px-6">
                <div class="max-w-2xl mx-auto relative z-10">
                    <h1 class="text-4xl md:text-5xl font-bold font-unbounded mb-8 text-center animate-on-scroll opacity-0">
                        <span class="animate-gradient-text">Поддержать проект</span>
                    </h1>
                    <p class="text-lg mb-12 text-gray-300 text-center max-w-xl mx-auto animate-on-scroll opacity-0 delay-100">
                        Помогите развитию Dream Mobile и получите дополнительные привилегии в игре
                    </p>
                    
                    <?php if (!empty($error_message)): ?>
                    <div class="bg-red-500 bg-opacity-20 border border-red-500 text-white p-4 rounded-xl mb-8 animate-on-scroll opacity-0 delay-50">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <div class="relative animate-on-scroll opacity-0 delay-200">
                        <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-600 to-blue-400 rounded-3xl blur opacity-20"></div>
                        <div class="backdrop-blur-sm bg-white/5 rounded-3xl p-8 relative z-10 border border-white/10">
                            <form action="process_payment.php" method="post" id="donation-form">
                                <div class="mb-8">
                                    <label for="nickname" class="block text-sm font-medium text-gray-300 mb-2">Ваш никнейм в игре</label>
                                    <input type="text" id="nickname" name="nickname" required 
                                        class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                </div>
                                
                                <div class="mb-10">
                                    <label for="amount" class="block text-sm font-medium text-gray-300 mb-2">Сумма доната (₽)</label>
                                    <div class="relative">
                                        <input type="number" id="amount" name="amount" min="10" required 
                                            class="w-full bg-black border border-white/10 rounded-xl px-4 py-3 text-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                                            <span class="text-gray-400">₽</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 py-4 rounded-full text-lg font-bold transition-all duration-500 transform hover:scale-105 btn-hover-effect">
                                    Оплатить
                                </button>
                                
                                <div class="mt-5 text-center text-sm text-gray-500">
                                    Нажимая кнопку "Оплатить", вы соглашаетесь с <a href="terms.php" class="text-blue-400 hover:underline">правилами сервера</a>
                                </div>
                            </form>
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