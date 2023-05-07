<?php

$title = 'Edit Quiz';
ob_start();

?>
<h1 class="mb-4">Edit Quiz</h1>

<form method="post" action="/quiz/update">
        <div class="form-group mb-3">
            <label for="question">Вопрос:</label>
            <input type="text" class="form-control" id="question" name="question" required value="<?=$quiz['question']?>">
            <input type="hidden" class="form-control" id="id" name="id" value="<?=$quiz['id']?>">
            <div class="question-suggestions"></div>
        </div>

        <div class="form-group mb-3">
            <label for="answer_1">Ответ 1:</label>
            <input type="text" class="form-control" id="answer_1" name="answer_1" required value="<?=$quiz['answer_1']?>">
        </div>
        <div class="form-group mb-3">
            <label for="answer_2">Ответ 2:</label>
            <input type="text" class="form-control" id="answer_2" name="answer_2" required value="<?=$quiz['answer_2']?>">
        </div>
        <div class="form-group mb-3">
            <label for="answer_3">Ответ 3:</label>
            <input type="text" class="form-control" id="answer_3" name="answer_3" required value="<?=$quiz['answer_3']?>">

        </div>
        <div class="form-group mb-3">
            <label for="correct_answer" class="form-label">Правильный ответ:</label>
            <select class="form-select" id="correct_answer" name="correct_answer" required>
                <option value="0" <?php echo $quiz['correct_answer'] === 0 ? "selected" : '';?>>Ответ 1</option>
                <option value="1" <?php echo $quiz['correct_answer'] === 1 ? "selected" : '';?>>Ответ 2</option>
                <option value="2" <?php echo $quiz['correct_answer'] === 2 ? "selected" : '';?>>Ответ 3</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="explanation" class="form-label">Объяснение:</label>
            <textarea class="form-control" id="explanation" name="explanation" rows="3"><?php echo $quiz['explanation'] ? $quiz['explanation'] : '';?></textarea>
        </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>


<?php $content = ob_get_clean(); 

include 'app/views/layout.php';
?>