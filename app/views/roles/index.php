<?php

$title = 'Roles';
ob_start(); 
?>

  <h1 class="mb-4">Roles</h1>
  <a href="index.php?page=roles&action=create" class="btn btn-success">Create role</a>
    <table class="table">
    <thead>
        <tr>
        <th>ID</th>
        <th>Role Name</th>
        <th>Role Description</th>
        <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($roles as $role): ?>
        <tr>
        <td><?= $role['id'] ?></td>
        <td><?= $role['role_name'] ?></td>
        <td><?= $role['role_description'] ?></td>
        <td>
            <a href="index.php?page=roles&action=edit&id=<?= $role['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
            <form method="POST" action="index.php?page=roles&action=delete&id=<?= $role['id'] ?>" class="d-inline-block">
            <!-- <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button> -->
            </form>
        </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>



<?php $content = ob_get_clean(); 

include 'app/views/layout.php';
?>