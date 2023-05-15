<?php
$title = 'All clicks info';
ob_start(); 

?>

<h1 class="mb-4">All clicks info</h1>

<div class="table-responsive">
    <table class="table table-bordered information_click">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Short Links</th>
                <th>IP User</th>
                <th>User Agent</th>
                <th>User Referer</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($informations as $inf): ?>
                <tr>
                    <td><?php echo $inf['id']; ?></td>
                    <td><?php echo $inf['id_short_links']; ?></td>
                    <td><?php echo $inf['ip_user']; ?></td>
                    <td><?php echo $inf['user_agent']; ?></td>
                    <td><?php echo $inf['user_referer']; ?></td>
                    <td><?php echo $inf['created_at']; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<?php $content = ob_get_clean(); 

include 'app/views/layout.php';
?>