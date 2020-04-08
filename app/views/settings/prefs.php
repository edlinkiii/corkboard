<?php require APPROOT . '/views/inc/header.php' ?>
<section>
  <form style="margin-top: 2rem;" method="post" action="<?php echo URLROOT ?>/settings/prefs">
    <h3><?php echo $data['title']; ?></h3>
    <div id="message">test</div>
    <hr id="divider" style="margin-bottom: 1rem;" />
    <label for="radio">Public? </label>
    <input type="radio" name="public" value="1" <?php echo $data['form']['public'] ? 'checked' : ''; ?>> Yes
    <input type="radio" name="public" value="0" <?php echo $data['form']['public'] ? '' : 'checked'; ?>> No
    <br />
    <label for="radio">Stalkable? </label>
    <input type="radio" name="stalkable" value="1" <?php echo $data['form']['stalkable'] ? 'checked' : ''; ?>> Yes
    <input type="radio" name="stalkable" value="0" <?php echo $data['form']['stalkable'] ? '' : 'checked'; ?>> No
    <br />
    <label for="radio">Display Birthdate? </label>
    <input type="radio" name="show_birthdate" value="1" <?php echo $data['form']['show_birthdate'] ? 'checked' : ''; ?>> Yes
    <input type="radio" name="show_birthdate" value="0" <?php echo $data['form']['show_birthdate'] ? '' : 'checked'; ?>> No
    <br />
    <label for="radio">Display Location? </label>
    <input type="radio" name="show_location" value="1" <?php echo $data['form']['show_location'] ? 'checked' : ''; ?>> Yes
    <input type="radio" name="show_location" value="0" <?php echo $data['form']['show_location'] ? '' : 'checked'; ?>> No
    <br />
    <br />
    <button type="submit">Save</button>
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
