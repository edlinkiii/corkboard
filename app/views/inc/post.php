  <article id="post_id-<?php echo $post->post_id; ?>" class="<?php if($nested && isset($post->post_reply_to_id)) echo 'nested-post'; ?>">
    <header>
  <?php if(isset($_SESSION['user_id']) && $post->user_id === $_SESSION['user_id']): ?>
      <a class="edit-button" style="float: right;" href="<?php echo URLROOT; ?>/posts/edit/<?php echo $post->post_id; ?>"><b><i class="fa fa-pencil-square-o"></i><span> Edit Post</span></b></a>
  <?php else: ?>
      <a class="edit-button favorite-button" style="float: right;"><b><i class="fa fa-heart<?php echo $post->is_favorite ? '' : '-o'; ?>"></i><span> <?php echo $post->is_favorite ? 'Unf' : 'F'; ?>avorite</span></b></a>
  <?php endif; ?>
      <img class="profile-pic-sm" src="<?php echo URLROOT; ?>/images/profile_pic/<?php echo $post->user_pic; ?>" />
      <h3><a href="<?php echo URLROOT; ?>/users/profile/<?php echo $post->user_id; ?>"><?php echo $post->user_name; ?></a></h3>
      <a class='show-post-link' href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->post_id; ?>"><?php echo date(DATETIME_FORMAT, strtotime($post->post_stamp)); ?></a>
      <?php if(!$nested && isset($post->post_reply_to_id)): ?>
        <br>
        <b>Replying to <a href="<?php echo URLROOT.'/posts/show/'.$post->post_reply_to_id; ?>"><?php echo $post->post_reply_user_name; ?></a></b>
      <?php endif; ?>
    </header>
    <hr />
    <p><?php echo $post->post_body; ?></p>
    <hr />
    <div class="post-interaction">
      <?php $post->my_reaction = $post->my_reaction ?: 0; ?>
      <div class="reaction-holder <?php echo $reaction_config[$post->my_reaction]->color_class; ?>">
        <i class="fa <?php echo $reaction_config[$post->my_reaction]->icon_class; ?>"></i>
        <span class="reaction-total"> <?php echo $post->post_reaction ?: 0; ?></span>
      </div>
      <?php $post->post_reply_count = $post->post_reply_count ?: 0; ?>
      <?php $post->my_reply_count = $post->my_reply_count ?: 0; ?>
      <div class="reply-holder <?php echo ($post->my_reply_count > 0) ? 'commented-blue' : 'uncommented-gray'; ?>">
        <i class="fa fa-comment<?php echo $post->my_reply_count > 0 ? '' : '-o'; ?>"></i> 
        <span class="reply-count"><?php echo $post->post_reply_count; ?></span>
      </div>
    </div>
  </article>
