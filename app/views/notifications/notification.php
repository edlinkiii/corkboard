<?php $type_name_array = explode(',',$notification->type_name); ?>
<article class="notification <?php echo (strtotime($notification->notification_seen) > 0) ? 'seen' : 'unseen'; ?>">
  <p>Your
      <?php echo ($notification->post_is_reply) ? 'reply' : 'post'; ?> from 
      <?php echo date(DATETIME_FORMAT, strtotime($notification->post_created)); ?> has 
      <?php echo (strtotime($notification->notification_seen) > 0) ? '' : 'recently had'; ?>
      <?php echo $notification->type_count; ?>
      <?php echo $type_name_array[($notification->type_count == 1 ? 0 : 1)]; ?>
  </p>
  <p><?php echo $notification->post_body; ?></p>
</article>
