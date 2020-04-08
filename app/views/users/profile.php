<?php require APPROOT . '/views/inc/header.php' ?>
      <article>
<?php if(isset($_SESSION['user_id']) && $data['profile']->user_id == $_SESSION['user_id']): ?>
        <div style="float:right; text-align:right;">
          <a href="<?php echo URLROOT; ?>/settings/profile" class="edit-button"><b style="margin-bottom: 10px;">Edit Profile</b></a><br />
          <a href="<?php echo URLROOT; ?>/settings/prefs" class="edit-button"><b>Preferences</b></a>
        </div>
<?php endif; ?>
        <img class="profile-pic-md" src="<?php echo URLROOT; ?>/images/profile_pic/<?php echo $data['profile']->pic; ?>" />
        <h3><?php echo $data['profile']->name; ?></h3>
        <div id="message"></div>
        <hr id="divider" style="margin-bottom: 1rem;" />
        <strong>Joined:</strong> <?php echo date(DATE_FORMAT, strtotime($data['profile']->created_at)); ?><br />
        <!--div class="side-by-side"-->
          <strong>Birthdate: </strong><?php echo date(DATE_FORMAT, strtotime($data['profile']->birthdate)); ?><br />
        <!--/div><div class="side-by-side"-->
          <strong>Location: </strong>n/a
        <!--/div-->
        <br />
        <br />
        <strong>Bio: </strong><br /><?php echo $data['profile']->bio; ?><br />
      </article>
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
      </article>
<?php endforeach; ?>
<?php if(isset($_SESSION['message'])): ?>
<script>
  $q('#message').text('<?php echo $_SESSION['message']; ?>').css('background','#259425').css('color', '#ffffff').show();
  $q('#divider').hide();
</script>
<?php unset($_SESSION['message']); endif; ?>
<?php require APPROOT . '/views/inc/footer.php' ?>
