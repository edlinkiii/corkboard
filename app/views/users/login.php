<?php require APPROOT . '/views/inc/header.php' ?>
<section>
  <form style="margin-top: 2rem;" method="post" action="<?php echo URLROOT ?>/users/login">
    <h3><?php echo $data['title']; ?></h3>
    <hr style="margin-bottom: 1rem;" />
    <label for="email">Email: </label> <input name="email" type="email" value="<?php echo $data['form']['email']; ?>" required /><br />
    <label for="password">Password: </label> <input name="password" type="password" value="<?php echo $data['form']['password']; ?>" minlength="6" required /><br />
    <input type="submit" value="Log In" />
  </form>
</section>
<?php if($data['form']['error']): ?>
<script>
  const showError = () => {
    new Shout({
      text: '<?php echo $data['form']['error']; ?>',
      backgroundColor: '#cf3030',
      fontColor: '#ffffff',
      duration: 6, // seconds
      width: '300px',
      allBold: true
    });
  }
  showError();
</script>
<?php endif; ?>
<?php require APPROOT . '/views/inc/footer.php' ?>
