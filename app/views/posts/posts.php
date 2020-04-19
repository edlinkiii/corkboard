<?php if(is_array($data['posts'])): ?>
  <?php foreach($data['posts'] as $post): ?>
    <?php require APPROOT . '/views/inc/post.php'; ?>
  <?php endforeach; ?>
  <?php if(isset($data['replies']) && is_array($data['replies'])): ?>
    <?php foreach($data['replies'] as $post): ?>
      <?php require APPROOT . '/views/inc/post.php'; ?>
    <?php endforeach; ?>
  <?php endif; ?>
<?php else: ?>
  <article>
    <img class="profile-pic-sm" src="<?php echo URLROOT; ?>/images/profile_pic/placeholder.png" />
    <h3>Post Unavailable</h3>
    <br />
    <hr />
    <p>The requested post doesn't exist or the account that posted it is set to private.</p>
  </article>
<?php endif; ?>
