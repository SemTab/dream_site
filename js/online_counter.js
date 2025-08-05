/**
 * Скрипт для обновления счетчика онлайна через AJAX
 */
document.addEventListener('DOMContentLoaded', function() {
    // Функция для обновления счетчика онлайна
    function updateOnlineCounter() {
        fetch('online_counter.php?ajax=1')
            .then(response => response.json())
            .then(data => {
                // Обновляем счетчик текущего онлайна
                const currentOnlineElement = document.getElementById('current-online');
                if (currentOnlineElement) {
                    currentOnlineElement.textContent = data.current;
                }
                
                // Обновляем счетчик максимального онлайна
                const maxOnlineElement = document.getElementById('max-online');
                if (maxOnlineElement) {
                    maxOnlineElement.textContent = data.max;
                }
            })
            .catch(error => {
                console.error('Ошибка при обновлении счетчика онлайна:', error);
            });
    }
    
    // Обновляем счетчик каждые 30 секунд
    setInterval(updateOnlineCounter, 30000);
    
    // Также обновляем при загрузке страницы
    updateOnlineCounter();
}); 