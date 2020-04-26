<?php

require APPROOT . '/views/inc/header.php';

if(is_array($data['notifications'])):

?>
    <a id="new-notifications"><b><span></span> New</b></a>
<?php

  foreach($data['notifications'] as $notification):

    require APPROOT . '/views/notifications/notification.php';

  endforeach;

endif;

require APPROOT . '/views/inc/footer.php';
