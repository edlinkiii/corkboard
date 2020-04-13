<?php require APPROOT . '/views/inc/header.php' ?>
      <article>
<?php if(isset($_SESSION['user_id']) && $data['profile']->user_id == $_SESSION['user_id']): ?>
        <div style="float:right; text-align:right;">
          <a href="<?php echo URLROOT; ?>/settings/profile" class="edit-button"><b style="margin-bottom: 10px;">Edit Profile</b></a><br />
          <a href="<?php echo URLROOT; ?>/settings/prefs" class="edit-button"><b>Preferences</b></a>
        </div>
<?php elseif($data['prefs']->public && $data['prefs']->stalkable): ?>
        <div style="float:right; text-align:right;">
          <a href="<?php echo URLROOT; ?>/users/<?php echo $data['stalking'] ? 'un' : ''; ?>stalk/<?php echo $data['profile']->user_id; ?>" class="edit-button"><b style="margin-bottom: 10px;" <?php echo $data['stalking'] ? 'class="reverse"' : ''; ?>>Stalk</b></a><br />
        </div>
<?php endif; ?>
        <img class="profile-pic-md" src="<?php echo URLROOT; ?>/images/profile_pic/<?php echo $data['profile']->pic; ?>" />
        <h3><?php echo $data['profile']->name; ?></h3>
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
  <?php foreach($data['posts'] as $post): ?>
      <article>
    <?php if(isset($_SESSION['user_id']) && $post->user_id === $_SESSION['user_id']): ?>
        <a class="edit-button" style="float: right;" href="<?php echo URLROOT; ?>/posts/edit/<?php echo $post->post_id; ?>"><b>Edit Post</b></a>
    <?php endif; ?>
        <img class="profile-pic-sm" src="<?php echo URLROOT; ?>/images/profile_pic/<?php echo $post->user_pic; ?>" />
        <h3><a href="<?php echo URLROOT; ?>/users/profile/<?php echo $post->user_id; ?>"><?php echo $post->user_name; ?></a></h3>
        <a class='show-post-link' href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->post_id; ?>"><?php echo date(DATETIME_FORMAT, strtotime($post->post_stamp)); ?></a>
        <hr />
        <p><?php echo $post->post_body; ?></p>
        <hr />
        <b>Rating: </b><?php echo $post->post_reaction ? $post->post_reaction : 0; ?>
      </article>
  <?php endforeach; ?>
<?php endif; ?>
<?php if(isset($_SESSION['message'])): ?>
<script>
  $q('#message').text('<?php echo $_SESSION['message']; ?>').css('background','#259425').css('color', '#ffffff').show();
  $q('#divider').hide();
</script>
<?php unset($_SESSION['message']); endif; ?>
<?php require APPROOT . '/views/inc/footer.php' ?>
