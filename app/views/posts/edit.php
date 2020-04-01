<?php require APPROOT . '/views/inc/header.php' ?>
<form id="login-form" method="POST" action="<?php echo URLROOT ?>/posts/edit/<?php echo $data['form']['id']; ?>">
  <h3><?php echo $data['title']; ?></h3>
  <div id="message"></div>
  <hr id="divider" style="margin-bottom: 1rem;" />
  <label for="body">Body: </label> <textarea style="width: 100%;box-sizing: border-box;" name="body" required><?php echo $data['form']['body']; ?></textarea><br />

  <div class="side-by-side">
    <button type="submit">Post</button>
  </div><div class="side-by-side right">
    <a href="<?php echo URLROOT ?>/posts/remove/<?php echo $data['form']['id']; ?>" class="delete-button"><b>Delete</b></a>
  </div>


</form>
<?php if($data['form']['error']): ?>
<script>
  $q('#message').text('<?php echo $data['form']['error']; ?>').css('background','#cf3030').css('color', '#ffffff').show();
  $q('#divider').hide();
</script>
<?php endif; ?>
<?php require APPROOT . '/views/inc/footer.php' ?>
