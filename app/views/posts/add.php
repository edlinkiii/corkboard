<?php require APPROOT . '/views/inc/header.php' ?>
<form id="login-form" method="post" action="<?php echo URLROOT ?>/posts/add">
  <h3><?php echo $data['title']; ?></h3>
  <div id="message"></div>
  <hr id="divider" style="margin-bottom: 1rem;" />
  <label for="body">Body: </label> <textarea id="add-body" name="body" class="post-body" required><?php echo $data['form']['body']; ?></textarea><br />
  <button type="submit"><i class="fa fa-floppy-o"></i> Post</button>
</form>
<?php if($data['form']['error']): ?>
<script>
  $q('#message').text('<?php echo $data['form']['error']; ?>').css('background','#cf3030').css('color', '#ffffff').show();
  $q('#divider').hide();
</script>
<?php endif; ?>
<?php require APPROOT . '/views/inc/footer.php' ?>
