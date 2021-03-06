<?php require APPROOT . '/views/inc/header.php' ?>
<form id="login-form" method="POST" action="<?php echo URLROOT ?>/posts/edit/<?php echo $data['form']['id']; ?>">
  <h3><?php echo $data['title']; ?></h3>
  <div id="message"></div>
  <hr id="divider" style="margin-bottom: 1rem;" />
  <label for="body">Body: </label> <textarea  id="add-body" name="body" class="post-body" required><?php echo $data['form']['body']; ?></textarea>
  <input type="hidden" id="img" name="img" value="<?php echo $data['form']['img']; ?>" />
  <div id="img-holder">
    <?php if($data['form']['img']): ?>
    <a id="remove-image"></a>
    <img class="post-pic" src="<?php echo URLBASE.'/images/post_pic/'.$data['form']['img']; ?>">
    <?php endif; ?>
  </div>
  <br />
  <div class="side-by-side">
    <button type="submit"><i class="fa fa-floppy-o"></i> Save</button>
    <button type="button" id="img-upload-trigger"><i class="fa fa-picture-o"></i> Image</button>
  </div><div class="side-by-side right">
    <a href="<?php echo URLROOT ?>/posts/remove/<?php echo $data['form']['id']; ?>" class="delete-button"><b><i class="fa fa-trash"></i> Delete</b></a>
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
