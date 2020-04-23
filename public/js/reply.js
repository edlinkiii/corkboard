function clickReplyListener({target}) {
  let postId = target.parents('article')[0].attr('id').substr(8);

  ajax({
    url: SESSION_CHECK_URL,
    callback: (check) => {
      if(check.user_id > 0) {
        let replyModal = new Modal({
          id: 'replyModal',
          width: 400,
          height: 236,
          title: 'Post A Reply',
          draggable: true,
          // classes: ['slim-bars'],
          content: '<textarea class="reply-body" id="reply-body"></textarea><input type="hidden" id="post-id" value="'+postId+'">',
          buttons: [
            {
              text: 'Post',
              classes: ['button-blue'],
              onClick: () => {
                let body = $q('#reply-body').val().trim();
                let data = { body: body };
                let url = REPLY_URL + postId;
                ajax({
                  url: url,
                  type: 'POST',
                  data: data,
                  callback: (resp) => {
                    if(resp.Error) {
                      let errorModal = new Modal({
                        title: 'error',
                        content: '<br>'+resp.Error+'<br><br>',
                        buttons: [
                          {
                            text: 'Close',
                            classes: ['button-red','button-close'],
                            onClick: function() {
                                errorModal.destroy();
                            }
                          }
                        ]
                      });
                    }
                    else {
                      $q('article#post_id-'+resp.post_id+' .reply-holder').removeClass(UNCOMMENTED_COLOR_CLASS).addClass(COMMENTED_COLOR_CLASS);
                      $q('article#post_id-'+resp.post_id+' .reply-holder i').removeClass(UNCOMMENTED_ICON_CLASS).addClass(COMMENTED_ICON_CLASS);
                      $q('article#post_id-'+resp.post_id+' .reply-count').text(resp.reply_count);
                      replyModal.destroy();
                    }
                  }
                })
              }
            },
            {
              text: 'Cancel',
              classes: ['button-red','button-close'],
              onClick: () => {
                replyModal.destroy();
              }
            }
          ]
        })
      }
      else {
        location.href = LOGIN_URL;
      }
    }
  })

}

$qa(".reply-holder i").forEach((el) => {
  el.addEventListener("click", clickReplyListener);
});
