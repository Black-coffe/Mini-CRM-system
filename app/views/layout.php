<?php 
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : 'no-name';
$user_role = isset($_SESSION['user_role']) ? $_SESSION['user_role'] : false;
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="/app/css/style.css">
    <script src="https://kit.fontawesome.com/6e56039614.js" crossorigin="anonymous"></script>
    <!-- flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <!-- JavaScript Calendar | https://fullcalendar.io/ -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.5/index.global.min.js'></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="sidebar col-md-3">
            <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="min-height: 900px;">
                <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
                    <span class="fs-4">Mini CRM</span>
                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <?php if($user_role == 5 || !ENABLE_PERMISSION_CHECK): ?>
                        <li class="nav-item">
                            <a href="/" class="nav-link <?= is_active('/') ?>" aria-current="page">
                                <svg class="bi me-2" width="16" height="16"><use xlink:href="/"></use></svg>
                                Home
                            </a>
                        </li>
                        <li>
                            <a href="/users" class="nav-link text-white <?= is_active('/users') ?>">
                                <svg class="bi me-2" width="16" height="16"><use xlink:href="/users"></use></svg>
                                Users
                            </a>
                        </li>
                        <li>
                            <a href="/roles" class="nav-link text-white <?= is_active('/roles') ?>">
                                <svg class="bi me-2" width="16" height="16"><use xlink:href="/roles"></use></svg>
                                Roles
                            </a>
                        </li>
                        <li>
                            <a href="/pages" class="nav-link text-white <?= is_active('/pages') ?>">
                                <svg class="bi me-2" width="16" height="16"><use xlink:href="/pages"></use></svg>
                                Pages
                            </a>
                        </li>
                        <li>
                            <a href="/todo/tasks/generate" class="nav-link text-white <?= is_active('/todo/tasks/generate') ?>">
                                <svg class="bi me-2" width="16" height="16"><use xlink:href="/todo/tasks/generate"></use></svg>
                                Generate test tasks
                            </a>
                        </li>
                        <hr>
                    <?php endif ?>
                    <h4>To do list</h4>
                    <li>
                        <a href="/todo/tasks" class="nav-link text-white <?= is_active('/todo/tasks') ?>">
                            <svg class="bi me-2" width="16" height="16"><use xlink:href="/todo/tasks"></use></svg>
                            Tasks (opened)
                        </a>
                    </li>
                    <li>
                        <a href="/todo/tasks/completed" class="nav-link text-white <?= is_active('/todo/tasks/completed') ?>">
                            <svg class="bi me-2" width="16" height="16"><use xlink:href="/todo/tasks/completed"></use></svg>
                            Tasks (completed)
                        </a>
                    </li>
                    <li>
                        <a href="/todo/tasks/expired" class="nav-link text-white <?= is_active('/todo/tasks/expired') ?>">
                            <svg class="bi me-2" width="16" height="16"><use xlink:href="/todo/tasks/expired"></use></svg>
                            Tasks (expired)
                        </a>
                    </li>
                    <li>
                        <a href="/todo/tasks/create" class="nav-link text-white <?= is_active('/todo/tasks/create') ?>">
                            <svg class="bi me-2" width="16" height="16"><use xlink:href="/todo/tasks/create"></use></svg>
                            Create task
                        </a>
                    </li>
                    <li>
                        <a href="/todo/category" class="nav-link text-white <?= is_active('/todo/category') ?>">
                            <svg class="bi me-2" width="16" height="16"><use xlink:href="/todo/category"></use></svg>
                            Category
                        </a>
                    </li>
                </ul>
                <hr>
                <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong><?=$user_email?></strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                    <!-- <li><a class="dropdown-item" href="#">New project...</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li> -->
                    <li><a class="dropdown-item" href="/users/profile">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="/auth/logout">Sign out</a></li>
                    <li><a class="dropdown-item" href="/auth/login">Sign in</a></li>
                </ul>
                </div>
            </div>
        </div>

        <div class="article col-md-9">
            <div class="container mt-4">
                <?php echo $content; ?>
            </div>
        </div>
    </div>
</div>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="/app/js/my.js"></script>
</body>
</html>
