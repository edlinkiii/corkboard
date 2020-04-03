<?php require APPROOT . '/views/inc/header.php' ?>
<section>
  <form style="margin-top: 2rem;" method="post" action="<?php echo URLROOT ?>/settings/password">
    <h3><?php echo $data['title']; ?></h3>
    <div id="message"></div>
    <hr id="divider" style="margin-bottom: 1rem;" />
    <label for="current_password">Current Password: </label>
    <input name="current_password" type="password" value="" minlength="6" required /><br />
    <label for="desired_password">Desired Password: </label>
    <input name="desired_password" type="password" value="" minlength="6" required /><br />
    <label for="confirm_password">Confirm Password: </label>
    <input name="confirm_password" type="password" value="" minlength="6" required /><br />
    <button type="submit">Change Password</button>
  </form>
</section>
<?php if($data['form']['error']): ?>
<script>
  $q('#message').text('<?php echo $data['form']['error']; ?>').css('background','#cf3030').css('color', '#ffffff').show();
  $q('#divider').hide();
</script>
<?php endif; ?>
<?php require APPROOT . '/views/inc/footer.php' ?>
