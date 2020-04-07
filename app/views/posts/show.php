<?php require APPROOT . '/views/inc/header.php' ?>
<article>
<?php if(isset($_SESSION['user_id']) && $data->user_id === $_SESSION['user_id']): ?>
  <a class="edit-button" style="float: right;" href="<?php echo URLROOT; ?>/posts/edit/<?php echo $data->post_id; ?>"><b>Edit Post</b></a>
<?php endif; ?>
  <img class="profile-pic-sm" src="<?php echo URLROOT; ?>/images/profile_pic/<?php echo $data->user_pic; ?>" />
  <h3><a href="<?php echo URLROOT; ?>/users/profile/<?php echo $data->user_id; ?>"><?php echo $data->user_name; ?></a></h3>
  <a class='show-post-link' href="<?php echo URLROOT; ?>/posts/show/<?php echo $data->post_id; ?>"><?php echo date(DATETIME_FORMAT, strtotime($data->post_stamp)); ?></a>
  <hr />
  <p><?php echo $data->post_body; ?></p>
</article>
<?php require APPROOT . '/views/inc/footer.php' ?>
