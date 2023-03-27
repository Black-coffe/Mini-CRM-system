<?php
if($_SERVER['REQUEST_URI'] == '/crm_for_telegram/index.php'){
    header('Location: /crm_for_telegram/');
    exit();
}

$title = 'Home page';
ob_start(); 
?>

<h1>Home page</h1>

<?php $content = ob_get_clean(); 

include 'app/views/layout.php';
?>