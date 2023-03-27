<!DOCTYPE html>
<html>
<head>
    <title><?= $title ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <div class="row">
        <div class="sidebar col-md-3">
            <div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="min-height: 900px;">
                <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                    <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
                    <span class="fs-4">Mini CRM</span>
                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item">
                        <a href="index.php" class="nav-link active" aria-current="page">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="index.php"></use></svg>
                        Home
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=users" class="nav-link text-white">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="index.php?page=users"></use></svg>
                        Users
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=roles" class="nav-link text-white">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="index.php?page=roles"></use></svg>
                        Roles
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=register" class="nav-link text-white">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="index.php?page=register"></use></svg>
                        Register
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=login" class="nav-link text-white">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="index.php?page=login"></use></svg>
                        Login
                        </a>
                    </li>
                    <li>
                        <a href="index.php?page=logout" class="nav-link text-white">
                        <svg class="bi me-2" width="16" height="16"><use xlink:href="index.php?page=logout"></use></svg>
                        Logout
                        </a>
                    </li>
                </ul>
                <hr>
                <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong>mdo</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1" style="">
                    <li><a class="dropdown-item" href="#">New project...</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><a class="dropdown-item" href="#">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Sign out</a></li>
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

</body>
</html>
