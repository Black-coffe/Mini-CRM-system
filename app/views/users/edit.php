<?php

$title = 'Edit user';
ob_start(); 
?>
<h1>Edit user</h1>

<form method="POST" action="index.php?page=users&action=update&id=<?php echo $user['id']; ?>">
  <div class="mb-3">
    <label for="username" class="form-label">Username</label>
    <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username']; ?>" required>
  </div>
  <div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
  </div>
  <div class="mb-3">
    <label for="role" class="form-label">Role</label>
    <select class="form-control" id="role" name="role">
    <?php foreach ($roles as $role): ?>
      <option value="<?php echo $role['id']; ?>" <?php echo $user['role'] == $role['id'] ? 'selected' : ''; ?>><?php echo $role['role_name']; ?></option>
    <?php endforeach; ?>
  </select>

  </div>
  <button type="submit" class="btn btn-primary">Save Changes</button>
</form>




<?php $content = ob_get_clean(); 

include 'app/views/layout.php';
?>