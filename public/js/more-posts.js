const formatPosts = (posts = []) => {
  let output = '';
  let nested = false;

  for(p in posts) {
    let post = posts[p];
    post.my_reaction = post.my_reaction || 0;
    post.post_reply_count = post.post_reply_count || 0;
    post.my_reply_count = post.my_reply_count || 0;

    output += '      <article id="'+ post.post_id +'" class="post-unseen '+ ((nested && post.post_reply_to_id) ? 'nested-post' : '') +'">\n';
    output += '        <header>\n';

    if(MY_USER_ID && post.user_id === MY_USER_ID) {
      output += '          <a class="edit-button" style="float: right;" href="'+ URLROOT +'/posts/edit/'+ post.post_id +'"><b><i class="fa fa-pencil-square-o"></i><span> Edit Post</span></b></a>\n';
    }
    else {
      output += '          <a class="edit-button favorite-button" style="float: right;"><b><i class="fa fa-heart'+ ((post.is_favorite) ? '' : '-o') +'"></i><span> '+ ((post.is_favorite) ? 'Unf' : 'F') +'avorite</span></b></a>\n';
    }

    output += '          <img class="profile-pic-sm" src="'+ URLROOT +'/images/profile_pic/'+ post.user_pic +'" />\n';
    output += '          <h3><a href="'+ URLROOT +'/users/profile/'+ post.user_id +'">'+ post.user_name +'</a></h3>\n';
    output += '          <a class="show-post-link" href="'+ URLROOT +'/posts/show/'+ post.post_id +'">'+ formatDate(post.post_stamp) +'</a>\n';

    if(!nested && post.post_reply_to_id) {
      output += '          <br />\n';
      output += '          <b>Replying to <a href="'+ URLROOT +'/posts/show/'+ post.post_reply_to_id +'">'+ post.post_reply_user_name +'</a></b>\n';
    }

    output += '        </header>\n';
    output += '        <hr />\n';
    output += '        <p class="post-content">'+ marked(userTag(hashTag(post.post_body))) +'</p>\n';
    output += '        <hr />\n';
    output += '        <div class="post-interaction">\n';
    output += '          <div class="reaction-holder '+ reactionConfig[post.my_reaction].color_class +'">\n';
    output += '            <i class="fa '+ reactionConfig[post.my_reaction].icon_class +'"></i>\n';
    output += '            <span class="reaction-total"> '+ (post.post_reaction || 0) +'</span>\n';
    output += '          </div>\n';
    output += '          <div class="reply-holder '+ ((post.my_reply_count > 0) ? 'commented-blue' : 'uncommented-gray') +'">\n';
    output += '            <i class="fa fa-comment'+ ((post.my_reply_count > 0) ? '' : '-o') +'"></i> \n';
    output += '            <span class="reply-count">'+ post.post_reply_count +'</span>\n';
    output += '          </div>\n';
    output += '        </div>\n';
    output += '      </article>\n';
  }

  // if(posts.length === POSTS_PER_PAGE) {
  //   output += '      <a id="more-posts"><b>More</b></a>\n'
  // }

  return output;
}

const userTag = (body) => {
  let taggedBody = body.replace(/\B@+\w+/g, (match) => {
    let username = match.substring(1);
    return '[@'+username+']('+URLBASE+'/u/'+username+')';
  });
  return taggedBody;
}

const hashTag = (body) => {
  let taggedBody = body.replace(/\B#+\w+/g, (match) => {
    let hashtag = match;
    return '['+hashtag+']('+URLBASE+'/search/'+hashtag+')';
  });
  return taggedBody;
}

const showPosts = (posts) => {
  $q('main section').append(formatPosts(posts));
}

const getMorePosts = () => {
  ajax({
    url: MORE_POSTS_URL,
    callback: (data) => showPosts(data.posts),
  });
}

const formatDate = (stamp) => {
  let d = new Date(stamp);
  let s = d.toString()
  let a = s.split(' ');
  let t = a[4].split(':');
  let m = (t[0] > 11) ? 'pm' : 'am' ;
  t[0] = (t[0] > 12) ? t[0]-12 : ((t[0] == 0) ? 12 : t[0]);
  return a[1] + ' ' + a[2] + ' ' + a[3] + ' @ ' + t[0] + ':' + t[1] + m;
}

// $q().on('click', 'a#more-posts b', () => {
//   $q('a#more-posts').remove();
//   getMorePosts();
// });

const areAnyPostsUnseen = () => {
  $qa('.post-unseen').forEach((u) => {
    let top = u.offset().top;
    if(top < window.innerHeight) {
      if($qa('.post-unseen').length === 2) {
        getMorePosts();
      }
      u.removeClass('post-unseen');
    }
  });
}

window.onscroll = () => areAnyPostsUnseen();

const reactionConfig = [
  {
    id: UNTOUCHED_ID,
    color_class: UNTOUCHED_COLOR_CLASS,
    icon_class: UNTOUCHED_ICON_CLASS,
  },
  {
    id: LIKE_ID,
    color_class: LIKE_COLOR_CLASS,
    icon_class: LIKE_ICON_CLASS,
  },
  {
    id: DISLIKE_ID,
    color_class: DISLIKE_COLOR_CLASS,
    icon_class: DISLIKE_ICON_CLASS,
  }
];
