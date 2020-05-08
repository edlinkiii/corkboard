<?php $type_name_array = explode(',',$notification->type_name); ?>
<article class="notification <?php echo (strtotime($notification->notification_seen) > 0) ? 'seen' : 'unseen'; ?>">
  <p>
    <?php if($notification->type_id == 4): ?>
      <a href="<?php echo URLBASE; ?>/u/<?php echo $notification->post_by_id; ?>"><?php echo $notification->post_by_name; ?></a>
      mentioned you in a post on <a href="<?php echo URLROOT; ?>/posts/show/<?php echo $notification->post_id; ?>"><?php echo date(DATETIME_FORMAT, strtotime($notification->post_created)); ?></a>
    <?php else: ?>
    Your
      <?php echo ($notification->post_is_reply) ? 'reply' : 'post'; ?> from 
      <a href="<?php echo URLROOT; ?>/posts/show/<?php echo $notification->post_id; ?>"><?php echo date(DATETIME_FORMAT, strtotime($notification->post_created)); ?></a> has 
      <?php echo (strtotime($notification->notification_seen) > 0) ? '' : 'recently had'; ?>
      <?php echo $notification->type_count; ?>
      <?php echo $type_name_array[($notification->type_count == 1 ? 0 : 1)]; ?>
    <?php endif; ?>
  </p>
  <hr>
  <p class="quote-post post-content"><?php echo $notification->post_body; ?></p>
</article>
