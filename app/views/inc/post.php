  <article id="post_id-<?php echo $post->post_id; ?>" class="<?php if(isset($post->post_parent_id)) echo 'nested-post'; ?>">
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
      <?php $post->my_reaction = $post->my_reaction ?: 0; ?>
      <span class="reaction-holder <?php echo $reaction_config[$post->my_reaction]->color_class; ?>">
        <i class="flaticon <?php echo $reaction_config[$post->my_reaction]->icon_class; ?>"></i>
        <span class="reaction-total"> <?php echo $post->post_reaction ?: 0; ?></span>
      </span>
    </div>
  </article>
