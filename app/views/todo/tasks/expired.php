<?php

$title = 'Todo list expired';
ob_start(); 
?>

    <h1 class="mb-4">Todo List expired</h1>
    <div class="d-flex justify-content-around row filter-priority">
        <a data-priority="low" class="btn mb-3 col-2 sort-btn" style="background: #51A5F4">Low</a>
        <a data-priority="medium" class="btn mb-3 col-2 sort-btn" style="background: #3C7AB5">Medium</a>
        <a data-priority="high" class="btn mb-3 col-2 sort-btn" style="background: #274F75">High</a>
        <a data-priority="urgent" class="btn mb-3 col-2 sort-btn" style="background: #122436">Urgent</a>
    </div>
            
    <div class="accordion" id="tasks-accordion">
        <?php foreach ($expiredTasks as $task): ?>
            <?php
                $priorityColor = '';
                switch ($task['priority']) {
                    case 'low':
                        $priorityColor = '#51A5F4';
                        break;
                    case 'medium':
                        $priorityColor = '#3C7AB5';
                        break;
                    case 'high':
                        $priorityColor = '#274F75';
                        break;
                    case 'urgent':
                        $priorityColor = '#122436';
                        break;
                    }
                ?>
            <div class="accordion-item mb-2">
                <div class="accordion-header d-flex justify-content-between align-items-center row" id="task-<?php echo $task['id']; ?>">
                    <h2 class="accordion-header col-12 col-md-6">
                        <button style="background: <?=$priorityColor?>;" class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#task-collapse-<?php echo $task['id']; ?>" aria-expanded="false" aria-controls="task-collapse-<?php echo $task['id']; ?>" data-priority="<?php echo $task['priority']; ?>">
                            <span class="col-12 col-md-5"><i class="fa-solid fa-square-up-right"></i> <strong><?php echo $task['title']; ?> </strong></span>
                            <span class="col-5 col-md-3"><i class="fa-solid fa-person-circle-question"></i> <?php echo $task['priority']; ?> </span>
                            <span class="col-5 col-md-3"><i class="fa-solid fa-hourglass-start"></i><span class="due-date"><?php echo $task['finish_date']; ?></span></span>
                        </button>
                    </h2>
                </div>
                <div id="task-collapse-<?php echo $task['id']; ?>" class="accordion-collapse collapse row" aria-labelledby="task-<?php echo $task['id']; ?>" data-bs-parent="#tasks-accordion">
                    <div class="accordion-body">
                        <p><strong><i class="fa-solid fa-layer-group"></i> Category:</strong> <?php echo htmlspecialchars($task['category_name'] ?? 'N/A'); ?></p>
                        <p><strong><i class="fa-solid fa-battery-three-quarters"></i> Status:</strong> <?php echo htmlspecialchars($task['status']); ?></p>
                        <p><strong><i class="fa-solid fa-person-circle-question"></i> Priority:</strong> <?php echo htmlspecialchars($task['priority']); ?></p>
                        <p><strong><i class="fa-solid fa-hourglass-start"></i> Due Date:</strong> <?php echo htmlspecialchars($task['finish_date']); ?></p>
                        <p><strong><i class="fa-solid fa-file-prescription"></i> Description:</strong> <?php echo htmlspecialchars($task['description'] ?? ''); ?></p>
                        <div class="d-flex justify-content-end">
                            <a href="/<?= APP_BASE_PATH ?>/todo/tasks/edit/<?php echo $task['id']; ?>" class="btn btn-primary me-2">Edit</a>
                            <a href="/<?= APP_BASE_PATH ?>/todo/tasks/delete/<?php echo $task['id']; ?>" class="btn btn-danger me-2">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>


<script>
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
</script>

<?php $content = ob_get_clean(); 

include 'app/views/layout.php';
?>