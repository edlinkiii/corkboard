<?php require APPROOT . '/views/inc/header.php' ?>
<section>
<form id="login-form" style="margin-top: 2rem;" method="POST" action="<?php echo URLROOT ?>/posts/edit/<?php echo $data['form']['id']; ?>">
    <h3><?php echo $data['title']; ?></h3>
    <div id="message"></div>
    <hr id="divider" style="margin-bottom: 1rem;" />
    <label for="body">Body: </label> <textarea name="body" required><?php echo $data['form']['body']; ?></textarea><br />
    <a href="<?php echo URLROOT ?>/posts/remove/<?php echo $data['form']['id']; ?>" class="delete-button"><b>Delete</b></a>
    <button type="submit">Post It!</button>
  </form>
</section>
<?php if($data['form']['error']): ?>
<script>
  $q('#message').text('<?php echo $data['form']['error']; ?>').css('background','#cf3030').css('color', '#ffffff').show();
  $q('#divider').hide();
</script>
<?php endif; ?>
<?php require APPROOT . '/views/inc/footer.php' ?>
