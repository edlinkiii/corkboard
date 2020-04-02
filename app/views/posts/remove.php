<?php require APPROOT . '/views/inc/header.php' ?>
<form id="login-form" method="POST" action="<?php echo URLROOT ?>/posts/remove/<?php echo $data['id']; ?>">
  <h3><?php echo $data['title']; ?></h3>
  <hr id="divider" style="margin-bottom: 1rem;" />
  <p><b>Are you sure?</b></p>
  <p><i>This cannot be undone.</i></p>
  <button class='delete-button' type="submit">Delete It!</button>
</form>
<?php require APPROOT . '/views/inc/footer.php' ?>
