<?php require APPROOT . '/views/inc/header.php' ?>
      <article>
<?php if(isset($_SESSION['user_id']) && $data['profile']->user_id == $_SESSION['user_id']): ?>
        <div style="float:right; text-align:right;">
          <a href="<?php echo URLROOT; ?>/settings/profile" class="edit-button"><b style="margin-bottom: 10px;"><i class="fa fa-user"></i><span> Edit Profile</span></b></a><br />
          <a href="<?php echo URLROOT; ?>/settings/prefs" class="edit-button"><b><i class="fa fa-cog"></i><span> Preferences</span></b></a>
        </div>
<?php elseif($data['prefs']->public && $data['prefs']->stalkable): ?>
        <div style="float:right; text-align:right;">
          <a href="<?php echo URLROOT; ?>/users/<?php echo $data['stalking'] ? 'un' : ''; ?>stalk/<?php echo $data['profile']->user_username; ?>" class="edit-button"><b style="margin-bottom: 10px;" <?php echo $data['stalking'] ? 'class="reverse"' : ''; ?>><i class="fa fa-binoculars"></i> <span><?php echo $data['stalking'] ? 'Unstalk' : 'Stalk'; ?></span></b></a><br />
        </div>
<?php endif; ?>
        <img class="profile-pic-md" src="<?php echo URLROOT; ?>/images/profile_pic/<?php echo $data['profile']->pic; ?>" />
        <h2><?php echo $data['profile']->name; ?></h2>
<?php if($data['profile']->user_id): ?>
        <div id="message"></div>
        <hr id="divider" style="margin-bottom: 1rem;" />
        <strong>Joined:</strong> <?php echo date(DATE_FORMAT, strtotime($data['profile']->created_at)); ?><br />
<?php if($data['prefs']->show_birthdate): ?>
        <strong>Birthdate: </strong><?php echo date(DATE_FORMAT, strtotime($data['profile']->birthdate)); ?><br />
<?php endif; ?>
<?php if($data['prefs']->show_location): ?>
        <strong>Location: </strong>n/a
<?php endif; ?>
        <br />
        <br />
<?php if(($data['prefs']->public) || (isset($_SESSION['user_id']) && $data['profile']->user_id == $_SESSION['user_id'])): ?>
        <strong>Bio: </strong><br /><?php echo $data['profile']->bio; ?><br />
<?php else: ?>
        <strong>This is a private account.</strong><br />
<?php endif; ?>
      </article>
<?php if(($data['prefs']->public) || (isset($_SESSION['user_id']) && $data['profile']->user_id == $_SESSION['user_id'])): ?>

  <?php require APPROOT . '/views/posts/posts.php' ?>

<?php endif; ?>
<?php if(isset($_SESSION['message'])): ?>
<script>
  $q('#message').text('<?php echo $_SESSION['message']; ?>').css('background','#259425').css('color', '#ffffff').show();
  $q('#divider').hide();
</script>
<?php unset($_SESSION['message']); endif; ?>
<?php endif; ?>
<?php require APPROOT . '/views/inc/footer.php' ?>
