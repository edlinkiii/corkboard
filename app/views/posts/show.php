<?php require APPROOT . '/views/inc/header.php' ?>
<?php if(is_array($data['posts'])): ?>
  <?php foreach($data['posts'] as $post): ?>
  <article id="post_id-<?php echo $post->post_id; ?>">
    <header>
  <?php if(isset($_SESSION['user_id']) && $post->user_id === $_SESSION['user_id']): ?>
      <a class="edit-button" style="float: right;" href="<?php echo URLROOT; ?>/posts/edit/<?php echo $post->post_id; ?>"><b><i class="flaticon flaticon-pen"></i><span> Edit Post</span></b></a>
  <?php endif; ?>
      <img class="profile-pic-sm" src="<?php echo URLROOT; ?>/images/profile_pic/<?php echo $post->user_pic; ?>" />
      <h3><a href="<?php echo URLROOT; ?>/users/profile/<?php echo $post->user_id; ?>"><?php echo $post->user_name; ?></a></h3>
      <a class='show-post-link' href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->post_id; ?>"><?php echo date(DATETIME_FORMAT, strtotime($post->post_stamp)); ?></a>
    </header>
    <hr />
    <p><?php echo $post->post_body; ?></p>
    <hr />
    <div class="post-interaction">
      <?php if($post->my_reaction == 1): ?>
      <span class="reaction-holder like-green"><i class="flaticon flaticon-like"></i><span class="reaction-total"> <?php echo $post->post_reaction ? $post->post_reaction : 0; ?></span></span>
      <?php elseif($post->my_reaction == -1): ?>
      <span class="reaction-holder dislike-red"><i class="flaticon flaticon-dislike"></i><span class="reaction-total"> <?php echo $post->post_reaction ? $post->post_reaction : 0; ?></span></span>
      <?php else: ?>
      <span class="reaction-holder untouched-gray"><i class="flaticon flaticon-like"></i><span class="reaction-total"> <?php echo $post->post_reaction ? $post->post_reaction : 0; ?></span></span>
      <?php endif; ?>
    </div>
  </article>
  <?php endforeach; ?>
<?php else: ?>
  <article>
    <img class="profile-pic-sm" src="<?php echo URLROOT; ?>/images/profile_pic/placeholder.png" />
    <h3>Post Unavailable</h3>
    <br />
    <hr />
    <p>The requested post doesn't exist or the account that posted it is set to private.</p>
  </article>
<?php endif; ?>
<?php require APPROOT . '/views/inc/footer.php' ?>
