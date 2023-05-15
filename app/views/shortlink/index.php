<?php
$title = 'Short links';
ob_start(); 

?>

<h1 class="mb-4">Short links</h1>

<a href="/shortlink/create" class="mb-3 btn btn-primary <?= is_active('/shortlink/create') ?>" role="button">
    Shortlinks create
</a>

<div class="accordion short_link" id="tasks-accordion">
    <?php foreach ($short_links as $short_link): ?>
        <div class="accordion-item mb-2">
            <h2 class="accordion-header" id="heading-<?php echo $short_link['id']; ?>">
                    <button class="accordion-button collapsed" 
                            type="button" 
                            data-bs-toggle="collapse" 
                            data-bs-target="#collapse-<?php echo $short_link['id']; ?>" 
                            aria-expanded="false" 
                            aria-controls="collapse-<?php echo $short_link['id']; ?>">
                        <?php echo $short_link['title_link']; ?>
                    </button>
                </h2>

                <div id="collapse-<?php echo $short_link['id']; ?>" 
                class="accordion-collapse collapse" 
                aria-labelledby="heading-<?php echo $short_link['id']; ?>" 
                data-bs-parent="#tasks-accordion">
                    <div class="accordion-body">
                        <p><strong>Amount click:</strong> <?php echo $short_link['clicks'] ?  $short_link['clicks'] : 0; ?></p>
                        <p><strong>Short Code:</strong> <?php echo $short_link['short_url']; ?></p>
                        <p><strong>Short URL:</strong> <a target="_blank" href="<?php echo "/". $short_link['short_url']; ?>"><?php echo $domain . "/" . $short_link['short_url']; ?></a></p>
                        <p><strong>Original URL:</strong> <a target="_blank" href="<?php echo $short_link['original_url']; ?>"><?php echo $short_link['original_url']; ?></a></p>
                        <p><strong>Created at:</strong> <?php echo $short_link['created_at']; ?></p>
                    </div>
                    <div class="d-flex justify-content-start action-quiz m-2">
                        <a href="/shortlink/edit/<?php echo $short_link['id']; ?>" class="btn btn-primary me-2">Edit</a>
                        <a href="/shortlink/delete/<?php echo $short_link['id']; ?>" class="btn btn-danger me-2">Delete</a>
                        <a href="/shortlink/information/<?php echo $short_link['id']; ?>" class="btn btn-info me-2">Information about clicks</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

<?php $content = ob_get_clean(); 

include 'app/views/layout.php';
?>