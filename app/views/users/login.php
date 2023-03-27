<?php

$title = 'Authorization';
ob_start(); 
?>

    <h1 class="mb-4">Authorization</h1>
    <form method="POST" action="index.php?page=auth&action=authenticate">
      <div class="mb-3">
        <label for="email" class="form-label">Email address</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
      </div>
      <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="remember" name="remember">
        <label class="form-check-label" for="remember">Remember me</label>
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
    </form>
    <div class="mt-3 text-center">
      Don't have an account? <a href="index.php?page=register">Register here</a>
    </div>

<?php $content = ob_get_clean(); 
include 'app/views/layout.php';
?>