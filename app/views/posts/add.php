<?php require APPROOT . '/views/inc/header.php' ?>
<form id="login-form" method="post" action="<?php echo URLROOT ?>/posts/add">
  <h3><?php echo $data['title']; ?></h3>
  <div id="message"></div>
  <hr id="divider" style="margin-bottom: 1rem;" />
  <label for="body">Body: </label> <textarea id="add-body" name="body" class="post-body" required><?php echo $data['form']['body']; ?></textarea>
  <input type="hidden" id="img" name="img" value="" />
  <div id="img-holder"></div>
  <br />
  <div class="side-by-side">
    <button type="submit"><i class="fa fa-floppy-o"></i> Post</button>
    <button type="button" id="img-upload-trigger"><i class="fa fa-picture-o"></i> Image</button>
  </div>
</form>
<input type="file" accept="image/jpeg, image/jpg, image/png, image/gif" id="img-upload" name="img-upload" style="display: none;" />
<?php if($data['form']['error']): ?>
<script>
  $q('#message').text('<?php echo $data['form']['error']; ?>').css('background','#cf3030').css('color', '#ffffff').show();
  $q('#divider').hide();
</script>
<?php endif; ?>
<?php require APPROOT . '/views/inc/footer.php' ?>
