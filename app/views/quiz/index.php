<?php

$title = 'Quiz';
ob_start(); 
?>
  <h1 class="mb-4">Quiz</h1>
  
  <div class="accordion quiz" id="tasks-accordion">
    <?php foreach ($quizes as $quiz): ?>
        <div class="accordion-item mb-2">
                <div class="accordion-header d-flex justify-content-between align-items-center row" id="quiz-<?php echo $quiz['id']; ?>">
                    <h2 class="accordion-header col-12 col-md-6">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                            data-bs-target="#quiz-collapse-<?php echo $quiz['id']; ?>" aria-expanded="false" 
                            aria-controls="quiz-collapse-<?php echo $quiz['id']; ?>">
                            <span class="col-12 col-md-5"><i class="fa-solid fa-square-up-right"></i> <strong><?php echo $quiz['question']; ?> </strong></span>
                        </button>
                    </h2>
                </div>
                <div id="quiz-collapse-<?php echo $quiz['id']; ?>" class="accordion-collapse collapse row" aria-labelledby="quiz-<?php echo $quiz['id']; ?>" data-bs-parent="#tasks-accordion">
                    <div class="accordion-body">
                        <div class="row">
                            <p class="col-12 col-md-12">
                                <strong><i class="fa-solid fa-layer-group"></i> 
                                Question:</strong> <?php echo $quiz['question']; ?></p>
                            <p class="col-12 col-md-12" <?php echo $quiz['correct_answer'] === 0 ? "style='color:green; font-weight:600'" : '';?>>
                                <strong><i class="fa-solid fa-battery-three-quarters"></i> 
                                Answer 1:</strong> <?php echo $quiz['answer_1']; ?>
                            </p>
                            <p class="col-12 col-md-12" <?php echo $quiz['correct_answer'] === 1 ? "style='color:green; font-weight:600'" : '';?>> 
                                <strong><i class="fa-solid fa-battery-three-quarters"></i> 
                                Answer 2:</strong> <?php echo $quiz['answer_2']; ?>
                            </p>
                            <p class="col-12 col-md-12" <?php echo $quiz['correct_answer'] === 2 ? "style='color:green; font-weight:600'" : '';?>>
                                <strong><i class="fa-solid fa-battery-three-quarters"></i> 
                                Answer 3:</strong> <?php echo $quiz['answer_3']; ?>
                            </p>
                            <p class="col-12 col-md-12">
                                <strong><i class="fa-solid fa-battery-three-quarters"></i> 
                                Explanation:</strong> <?php echo $quiz['explanation']; ?>
                            </p>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-start action-quiz">
                            <a href="/quiz/edit/<?php echo $quiz['id']; ?>" class="btn btn-primary me-2">Edit</a>
                            <a href="/quiz/delete/<?php echo $quiz['id']; ?>" class="btn btn-danger me-2">Delete</a>
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
  



<?php $content = ob_get_clean(); 

include 'app/views/layout.php';
?>