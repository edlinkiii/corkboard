<?php

require APPROOT . '/views/inc/header.php';

if(is_array($data['notifications'])):

?>
    <div id="new-notifications"><a href="<?php echo URLROOT; ?>/notifications"><b><span></span> New</b></a></div>
<?php

  foreach($data['notifications'] as $notification):

    require APPROOT . '/views/notifications/notification.php';

  endforeach;

endif;

require APPROOT . '/views/inc/footer.php';
