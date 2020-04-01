<?php require APPROOT . '/views/inc/header.php' ?>
<section>
  <form id="login-form" style="margin-top: 2rem;" method="post" action="<?php echo URLROOT ?>/users/login">
    <h3><?php echo $data['title']; ?></h3>
    <div id="message">test</div>
    <hr id="divider" style="margin-bottom: 1rem;" />
    <label for="email">Email: </label> <input name="email" type="email" value="<?php echo $data['form']['email']; ?>" required /><br />
    <label for="password">Password: </label> <input name="password" type="password" value="<?php echo $data['form']['password']; ?>" minlength="6" required /><br />
    <button type="submit">Login</button>
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
