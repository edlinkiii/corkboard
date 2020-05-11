const showPosts = (posts = []) => {
  let output = '';
  for(post in posts) {
    post.my_reaction = post.my_reaction || 0;
    post.post_reply_count = post.post_reply_count || 0;
    post.my_reply_count = post.my_reply_count || 0;

    output += '      <article id="'+ post.post_id +'" class="'+ ((nested && post.post_reply_to_id) ? 'nested-post' : '') +'">\n';
    output += '        <header>\n';

    if(my_user_id && post.user_id === my_user_id) {
      output += '          <a class="edit-button" style="float: right;" href="'+ URLROOT +'/posts/edit/'+ post.post_id +'"><b><i class="fa fa-pencil-square-o"></i><span> Edit Post</span></b></a>\n';
    }
    else {
      output += '          <a class="edit-button favorite-button" style="float: right;"><b><i class="fa fa-heart'+ ((post.is_favorite) ? '' : '-o') +'"></i><span> '+ ((post.is_favorite) ? 'Unf' : 'F') +'avorite</span></b></a>\n';
    }

    output += '          <img class="profile-pic-sm" src="'+ URLROOT +'/images/profile_pic/'+ post.user_pic +'" />\n';
    output += '          <h3><a href="'+ URLROOT +'/users/profile/'+ post.user_id +'">'+ post.user_name +'</a></h3>\n';
    output =+ '      <a class="show-post-link" href="'+ URLROOT +'/posts/show/'+ post.post_id +'">'+ date(DATETIME_FORMAT, strtotime(post.post_stamp)) +'</a>\n';

    if(!nested && post.post_reply_to_id) {
      output += '          <br />\n';
      output += '          <b>Replying to <a href="'+ URLROOT +'/posts/show/'+ post.post_reply_to_id +'">'+ post.post_reply_user_name +'</a></b>\n';
    }

    output += '        </header>\n';
    output += '        <hr />\n';
    output += '        <p class="post-content">'+ post.post_body +'</p>\n';
    output += '        <hr />\n';
    output += '        <div class="post-interaction">\n';
    output += '          <div class="reaction-holder '+ reaction_config[post.my_reaction].color_class +'">\n';
    output += '            <i class="fa '+ reaction_config[post.my_reaction].icon_class +'"></i>\n';
    output += '            <span class="reaction-total"> '+ (post.post_reaction || 0) +'</span>\n';
    output += '          </div>\n';
    output += '          <div class="reply-holder '+ ((post.my_reply_count > 0) ? 'commented-blue' : 'uncommented-gray') +'">\n';
    output += '            <i class="fa fa-comment'+ ((post.my_reply_count > 0) ? '' : '-o') +'"></i> \n';
    output += '            <span class="reply-count">'+ post.post_reply_count +'</span>\n';
    output += '          </div>\n';
    output += '        </div>\n';
    output += '      </article>\n';
  }
}
