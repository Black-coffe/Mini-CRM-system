<?php $title = 'Generate Tasks'; ?>
<?php ob_start(); ?>

<h1 class="mb-4"><?= $title ?></h1>

<form method="POST" action="/todo/tasks/generatestore">
  <div class="row">
    <!-- Categories range -->
    <div class="mb-3 col-12 col-md-6">
      <label for="categories_range" class="form-label">Categories Range</label>
      <input type="text" class="form-control" id="categories_range" name="categories_range" placeholder="Min,Max" required>
    </div>
    <!-- Users range -->
    <div class="mb-3 col-12 col-md-6">
      <label for="users_range" class="form-label">Users Range</label>
      <input type="text" class="form-control" id="users_range" name="users_range" placeholder="Min,Max" required>
    </div>
  </div>
  <div class="row">
    <!-- Created at range -->
    <div class="mb-3 col-12 col-md-4">
      <label for="created_at_range" class="form-label">Created At Range</label>
      <input type="text" class="form-control flatpickr" id="created_at_range" name="created_at_range" placeholder="Start,End" required>
    </div>
    <!-- Finish date range -->
    <div class="mb-3 col-12 col-md-4">
      <label for="finish_date_range" class="form-label">Finish Date Range</label>
      <input type="text" class="form-control flatpickr" id="finish_date_range" name="finish_date_range" placeholder="Start,End" required>
    </div>
    <!-- Count -->
    <div class="mb-3 col-12 col-md-4">
      <label for="count" class="form-label">Count test tasks</label>
      <input type="number" class="form-control" id="count" name="count" value="1000" required>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">Generate Tasks</button>
</form>

<script>
  document.addEventListener("DOMContentLoaded", function(){
    flatpickr(".flatpickr", {
      mode: "range",
      minDate: "today",
      dateFormat: "Y-m-d",
    });
  });
</script>

<?php $content = ob_get_clean(); ?>
<?php include 'app/views/layout.php'; ?>
