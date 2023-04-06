// Обновление даты до истечения срока задачи в заголовке акордеона
function updateRemainingTime() {
    const dueDateElements = document.querySelectorAll('.due-date');
    const now = new Date();

    dueDateElements.forEach((element) => {
        const dueDate = new Date(element.textContent);
        const timeDiff = dueDate - now;

        if (timeDiff > 0) {
            const days = Math.floor(timeDiff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeDiff % (1000 * 60 * 60)) / (1000 * 60));

            element.textContent = `Days: ${days} Hours: ${hours}`;
        } else {
            element.textContent = 'Time is up';
        }
    });
}

updateRemainingTime();
setInterval(updateRemainingTime, 60000); // Update every minute


// Сортировка задач по приоритетам для страниц index, completed, expired
document.addEventListener('DOMContentLoaded', function() {
    const sortButtons = document.querySelectorAll('.sort-btn');

    sortButtons.forEach((button) => {
        button.addEventListener('click', function() {
            const priority = button.getAttribute('data-priority');
            sortTasksByPriority(priority);
        });
    });
});

function sortTasksByPriority(priority) {
    const tasksAccordion = document.querySelector('#tasks-accordion');
    const tasks = Array.from(tasksAccordion.querySelectorAll('.accordion-item'));

    tasks.sort((a, b) => {
        const aPriority = a.querySelector('.accordion-button').getAttribute('data-priority');
        const bPriority = b.querySelector('.accordion-button').getAttribute('data-priority');

        if (aPriority === priority && bPriority !== priority) {
            return -1;
        } else if (aPriority !== priority && bPriority === priority) {
            return 1;
        } else {
            return 0;
        }
    });

    tasks.forEach((task) => {
        tasksAccordion.appendChild(task);
    });
}