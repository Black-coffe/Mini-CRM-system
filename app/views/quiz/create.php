<?php

$title = 'Create quiz';
ob_start(); 

?>

  <h1 class="mb-4">Create quiz</h1>
  <form method="post" action="/quiz/store">
        <div class="form-group mb-3">
            <label for="question">Вопрос:</label>
            <input type="text" class="form-control" id="question" name="question" required>
            <div class="question-suggestions"></div>
        </div>

        <div class="form-group mb-3">
            <label for="answer_1">Ответ 1:</label>
            <input type="text" class="form-control" id="answer_1" name="answer_1" required>
        </div>
        <div class="form-group mb-3">
            <label for="answer_2">Ответ 2:</label>
            <input type="text" class="form-control" id="answer_2" name="answer_2" required>
        </div>
        <div class="form-group mb-3">
            <label for="answer_3">Ответ 3:</label>
            <input type="text" class="form-control" id="answer_3" name="answer_3" required>

        </div>
        <div class="form-group mb-3">
            <label for="correct_answer" class="form-label">Правильный ответ:</label>
            <select class="form-select" id="correct_answer" name="correct_answer" required>
                <option value="0">Ответ 1</option>
                <option value="1">Ответ 2</option>
                <option value="2">Ответ 3</option>
            </select>
        </div>
        <div class="form-group mb-3">
            <label for="explanation" class="form-label">Объяснение:</label>
            <textarea class="form-control" id="explanation" name="explanation" rows="3"></textarea>
        </div>
            <button type="submit" class="btn btn-primary">Сохранить</button>
    </form>
<script>
  const questionInput = document.getElementById('question');
  const suggestionsContainer = document.querySelector('.question-suggestions');

  questionInput.addEventListener('input', (event) => {
    const inputValue = event.target.value;
    if (inputValue.length < 3) {
      suggestionsContainer.innerHTML = '';
      suggestionsContainer.style.display = 'none';
      return;
    }

    fetch('/quiz/search', {
      method: 'POST',
      body: JSON.stringify({question: inputValue}),
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      // очищаем контейнер с предыдущими результатами поиска
      suggestionsContainer.innerHTML = '';
      // если есть совпадения, выводим их в контейнер
      if (data.length > 0) {
        const ul = document.createElement('ul');
        data.forEach(item => {
          const li = document.createElement('li');
          li.textContent = item.question;
          li.addEventListener('click', () => {
            questionInput.value = item.question;
            suggestionsContainer.style.display = 'none';
          });
          ul.appendChild(li);
        });
        suggestionsContainer.appendChild(ul);
        suggestionsContainer.style.display = 'block';
      } else {
        suggestionsContainer.style.display = 'none';
      }
    })
    .catch(error => console.error(error));
  });
</script>

<?php $content = ob_get_clean(); 

include 'app/views/layout.php';
?>