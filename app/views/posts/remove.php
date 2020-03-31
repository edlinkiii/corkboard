<?php require APPROOT . '/views/inc/header.php' ?>
<section>
<form id="login-form" style="margin-top: 2rem;" method="POST" action="<?php echo URLROOT ?>/posts/remove/<?php echo $data['id']; ?>">
    <h3><?php echo $data['title']; ?></h3>
    <hr id="divider" style="margin-bottom: 1rem;" />
    <p><b>Are you sure?</b></p>
    <p><i>This cannot be undone.</i></p>
    <button type="submit">Delete It!</button>
  </form>
</section>
<?php require APPROOT . '/views/inc/footer.php' ?>
