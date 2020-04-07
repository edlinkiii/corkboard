<?php require APPROOT . '/views/inc/header.php' ?>
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
<?php require APPROOT . '/views/inc/footer.php' ?>
