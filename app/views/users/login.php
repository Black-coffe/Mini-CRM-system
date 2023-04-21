<?php $title = "Login"; ob_start(); ?>

<h1>Login</h1>

<form method="POST" action="index.php?page=login">
    <div class="form-group">
        <label for="login">Login</label>
        <input type="text" class="form-control" id="login" name="login" required>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Login</button>
</form>

<?php $content = ob_get_clean(); ?>
<?php include 'app/views/layout.php'; ?>

