<?php

$title = 'Edit shortlink';
ob_start(); 
?>

  <h1 class="mb-4">Edit shortlink</h1>

    <form method="POST" action="/shortlink/update">
        <div class="mb-3">
            <label for="title_link" class="form-label">Title Link</label>
            <input type="text" class="form-control" id="title_link" name="title_link" value="<?=$short_link['title_link'];?>" required>
            <input type="hidden" name="short_link_id" value="<?=$short_link['id'];?>">
        </div>
        <div class="mb-3">
            <label for="original_url" class="form-label">Original URL</label>
            <input type="url" class="form-control" id="original_url" name="original_url" value="<?=$short_link['original_url'];?>" required>
            <div class="form-text">
                <ul>
                    <li>Поле должно содержать минимум 10 символов</li>
                    <li>Первые символы поля всегда должны начинаться вот так: "https://"</li>
                    <li>В поле должна быть минимум одна точка "." это проверяет что домен и зона разделены точкой</li>
                    <li>В поле должен быть минимум один знак "/"</li>
                </ul>
            </div>
        </div>
        <div class="mb-3">
            <label for="short_code" class="form-label">Short code</label>
            <input type="text" class="form-control" id="short_code" name="short_code" value="<?=$short_link['short_url'];?>">
            <small class="form-text text-muted">Необязательное поле. Если поле останется пустым, будет сгенерирован случайный код.</small>
            <div class="form-text">
                <ul>
                    <li>Только английские символы</li>
                    <li>Никаких спецсимволов, разрешено только знак "-" разделитель</li>
                    <li>В ссылке не должны быть пробелы</li>
                    <li>Первый символ всегда начинается с буквы, не с цифры</li>
                    <li>Длина не менее 5 символов</li>
                </ul>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Update link</button>
    </form>

<?php $content = ob_get_clean(); 

include 'app/views/layout.php';
?>