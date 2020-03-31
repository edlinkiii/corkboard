<?php require APPROOT . '/views/inc/header.php' ?>
<section>
  <form style="margin-top: 2rem;" method="post" action="<?php echo URLROOT ?>/users/signup">
    <h3><?php echo $data['title']; ?></h3>
    <div id="message">test</div>
    <hr id="divider" style="margin-bottom: 1rem;" />
    <label for="name">Name: </label>
    <input name="name" type="text" value="<?php echo $data['form']['name']; ?>" required /><br />
    <label for="email">Email: </label>
    <input name="email" type="email" value="<?php echo $data['form']['email']; ?>" required /><br />
    <label for="password">Password: </label>
    <input name="password" type="password" value="<?php echo $data['form']['password']; ?>" minlength="6" required /><br />
    <label for="password">Confirm Password: </label>
    <input name="confirm_password" type="password" value="<?php echo $data['form']['confirm_password']; ?>" minlength="6" required /><br />
    <input type="submit" value="Register" />
  </form>
</section>
<?php if($data['form']['error']): ?>
<script>
  $q('#message').text('<?php echo $data['form']['error']; ?>').css('background','#cf3030').css('color', '#ffffff').show();
  $q('#divider').hide();
</script>
<?php endif; ?>
<?php require APPROOT . '/views/inc/footer.php' ?>
