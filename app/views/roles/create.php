<?php

$title = 'Create role';
ob_start(); 
?>

  <h1 class="mb-4">Create role</h1>
    <form method="POST" action="index.php?page=roles&action=store">
    <div class="mb-3">
        <label for="role_name" class="form-label">Role Name</label>
        <input type="text" class="form-control" id="role_name" name="role_name" required>
    </div>
    <div class="mb-3">
        <label for="role_description" class="form-label">Role Description</label>
        <textarea class="form-control" id="role_description" name="role_description" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Create Role</button>
    </form>

<?php $content = ob_get_clean(); 

include 'app/views/layout.php';
?>