<?php

$title = 'Create page';
ob_start(); 

?>

  <h1 class="mb-4">Create page</h1>
    <form method="POST" action="/pages/store">
    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="slug" class="form-label">Slug</label>
        <input type="text" class="form-control" id="slug" name="slug" required>
    </div>
    <div id="roles-container" class="mb-3">
        <label for="roles" class="form-label">Roles</label>
        <?php foreach ($roles as $role): ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="roles[]" value="<?php echo $role['id']; ?>">
                <label class="form-check-label" for="roles"><?php echo $role['role_name']; ?></label>
            </div>
        <?php endforeach; ?>
    </div>
    <button type="submit" class="btn btn-primary">Create page</button>
    </form>

<?php $content = ob_get_clean(); 

include 'app/views/layout.php';
?>