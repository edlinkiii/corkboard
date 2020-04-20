<?php

if(is_array($data['posts'])):

  $nested = false;

  foreach($data['posts'] as $post):

    require APPROOT . '/views/inc/post.php';

  endforeach;

  if(isset($data['replies']) && is_array($data['replies'])):

    $nested = true;

    foreach($data['replies'] as $post):

      require APPROOT . '/views/inc/post.php';

    endforeach;

  endif;

else: ?>
  <article>
    <img class="profile-pic-sm" src="<?php echo URLROOT; ?>/images/profile_pic/placeholder.png" />
    <h3>Post Unavailable</h3>
    <br />
    <hr />
    <p>The requested post doesn't exist or the account that posted it is set to private.</p>
  </article>
<?php endif;
