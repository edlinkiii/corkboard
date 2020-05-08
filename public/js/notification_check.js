let first = true;

function checkNotifications() {
  ajax({
    url: URLROOT + '/notifications/check',
    callback: (data) => {
      $q('#notification_alert').text(data.unseen);
      if(data.unseen > 0) {
        if(ACTIVE_LINK == 'notifications') {
          $q('#notification_alert').hide();
          $q('#new-notifications span').text(data.unseen);
          if(!first) {
            $q('#new-notifications b').show();
            $q('#notification_alert').show();
          }
        }
        else {
          $q('#notification_alert').text(data.unseen);
          $q('#notification_alert').show();
        }
      }
      else {
        $q('#notification_alert').hide();
      }
      first = false;
    }
  })
}

checkNotifications();

setInterval(() => {
  checkNotifications();
}, 5000);
