<?php require APPROOT . '/views/inc/header.php' ?>
<section>
  <form style="margin-top: 2rem;" method="post" action="<?php echo URLROOT ?>/settings/profile">
    <h3><?php echo $data['title']; ?></h3>
    <div id="message">test</div>
    <hr id="divider" style="margin-bottom: 1rem;" />
    <div class="">
      <label for="name">Name: </label>
      <input class="full-width" name="name" type="text" value="<?php echo $data['form']['name']; ?>" required /><br />
    </div>
    <div class="side-by-side" style="padding-right: 20px;">
      <label for="birthdate">Birthdate: </label>
      <input class="full-width" name="birthdate" type="text" value="<?php echo $data['form']['birthdate']; ?>" /><br />
    </div><div class="side-by-side">
      <label for="location">Location: </label>
      <input class="full-width" name="location" type="text" value="<?php echo $data['form']['location']; ?>" disabled /><br />
    </div>
    <label for="bio">Bio: </label>
    <textarea class="full-width" name="bio"><?php echo $data['form']['bio']; ?></textarea><br />
    <button type="submit">Save Profile</button>
    <a href="<?php echo URLROOT ?>/settings/password"><b>Change Password</b></a>
    <a href="<?php echo URLROOT ?>/settings/pic"><b>Change Profile Pic</b></a>
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
