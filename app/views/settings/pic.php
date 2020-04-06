<?php require APPROOT . '/views/inc/header.php' ?>
<section>
  <form style="margin-top: 2rem;" method="POST" action="<?php echo URLROOT ?>/settings/pic" enctype="multipart/form-data">
    <h3><?php echo $data['title']; ?></h3>
    <div id="message"></div>
    <hr id="divider" style="margin-bottom: 1rem;" />
    <label for="name">File to upload: </label>
    <input type="file" name="fileToUpload" required />
    <button type="submit">Upload Image</button>
  </form>
</section>
<?php if($data['form']['error']): ?>
<script>
  $q('#message').text('<?php echo $data['form']['error']; ?>').css('background','#cf3030').css('color', '#ffffff').show();
  $q('#divider').hide();
</script>
<?php endif; ?>
<?php if(isset($_SESSION['message'])): ?>
<script>
  $q('#message').text('<?php echo $_SESSION['message']; ?>').css('background','#259425').css('color', '#ffffff').show();
  $q('#divider').hide();
</script>
<?php unset($_SESSION['message']); endif; ?>
<?php require APPROOT . '/views/inc/footer.php' ?>
