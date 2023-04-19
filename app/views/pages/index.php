<?php

$title = 'Pages';
ob_start(); 
?>

  <h1 class="mb-4">Pages</h1>
  <a href="/pages/create" class="btn btn-success">Create page</a>
    <table class="table">
    <thead>
        <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Slug</th>
        <th>Role</th>
        <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($pages as $page): ?>
        <tr>
        <td><?= $page['id'] ?></td>
        <td><?= $page['title'] ?></td>
        <td><?= $page['slug'] ?></td>
        <td><?= $page['role'] ?></td>
        <td>
            <a href="/pages/edit/<?= $page['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
            <form method="POST" action="/pages/delete/<?= $page['id'] ?>" class="d-inline-block">
            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>



<?php $content = ob_get_clean(); 

include 'app/views/layout.php';
?>