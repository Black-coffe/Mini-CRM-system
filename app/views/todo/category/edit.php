<?php

$title = 'Edit Category';
ob_start(); 
?>

  <h1 class="mb-4">Edit Category</h1>
    <form method="POST" action="/todo/category/update/<?php echo $category['id']; ?>">
    <input type="hidden" name="id" value="<?= $category['id'] ?>">
    <div class="mb-3">
        <label for="title" class="form-label">Role Name</label>
        <input type="text" class="form-control" id="title" name="title" value="<?= $category['title'] ?>" required>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Category Description</label>
        <textarea class="form-control" id="description" name="description" required><?= $category['description'] ?></textarea>
    </div>
    <div class="mb-3">
        <label for="category_usability" class="form-label">Usability</label>
        <input type="checkbox" class="form-check-input" id="category_usability" name="usability" value="1" <?php echo $category['usability'] ? ' checked' : '';?>>
    </div>
    <button type="submit" class="btn btn-primary">Update Category</button>
    </form>

<?php $content = ob_get_clean(); 

include 'app/views/layout.php';
?>