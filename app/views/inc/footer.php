    </section>
  </main>
  <script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const URLBASE = '<?php echo URLBASE; ?>';
    const LOGIN_URL = URLBASE+'/users/login';
    const SESSION_CHECK_URL = URLBASE + '/users/check';
    const REACTION_URL = URLBASE + '/posts/react/';
    const ICON_CLASS = 'fa';
    const ICON_TAG = 'i';
    const UNTOUCHED_ID = <?php echo $reaction_config[0]->id; ?>;
    const UNTOUCHED_COLOR_CLASS = '<?php echo $reaction_config[0]->color_class; ?>';
    const UNTOUCHED_ICON_CLASS = '<?php echo $reaction_config[0]->icon_class; ?>';
    const LIKE_ID = <?php echo $reaction_config[1]->id; ?>;
    const LIKE_COLOR_CLASS = '<?php echo $reaction_config[1]->color_class; ?>';
    const LIKE_ICON_CLASS = '<?php echo $reaction_config[1]->icon_class; ?>';
    const DISLIKE_ID = <?php echo $reaction_config[2]->id; ?>;
    const DISLIKE_COLOR_CLASS = '<?php echo $reaction_config[2]->color_class; ?>';
    const DISLIKE_ICON_CLASS = '<?php echo $reaction_config[2]->icon_class; ?>';
    const REPLY_URL = URLBASE + '/posts/reply/';
    const UNCOMMENTED_COLOR_CLASS = 'uncommented-gray';
    const UNCOMMENTED_ICON_CLASS = 'fa-comment-o';
    const COMMENTED_COLOR_CLASS = 'commented-blue';
    const COMMENTED_ICON_CLASS = 'fa-comment';
    const FAVORITES_URL = URLBASE + '/favorites';
    const FAVORITED_ICON_CLASS = 'fa-heart';
    const UNFAVORITED_ICON_CLASS = 'fa-heart-o';
    const POST_URL = URLBASE+'/posts/show/';
    const MORE_POSTS_URL = URLBASE+'/posts/more';
    const POSTS_PER_PAGE = <?php echo POSTS_PER_PAGE; ?>;
    const MY_USER_ID = '<?php echo $_SESSION['user_id']; ?>';
    const MY_USER_NAME = '<?php echo $_SESSION['user_username']; ?>';
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/1.0.0/marked.min.js"></script>
  <script src="<?php echo URLBASE; ?>/js/lib/myui.js"></script>
  <script src="<?php echo URLBASE; ?>/js/reaction.js"></script>
  <script src="<?php echo URLBASE; ?>/js/reply.js"></script>
  <script src="<?php echo URLBASE; ?>/js/favorite.js"></script>
  <script src="<?php echo URLBASE; ?>/js/markdown.js"></script>
  <script src="<?php echo URLBASE; ?>/js/mention.js"></script>
  <script src="<?php echo URLBASE; ?>/js/more-posts.js"></script>
<?php if(isset($_SESSION['user_id'])): ?>
  <script src="<?php echo URLBASE; ?>/js/notification_check.js"></script>
<?php endif; ?>
  <script>
    <?php if(isset($_SESSION['active_link']) && $_SESSION['active_link'] != ''): ?>
    const ACTIVE_LINK = '<?php echo $_SESSION['active_link']; ?>';
    let activeLink = $q('#sidebar a#'+ACTIVE_LINK);
    if(activeLink) activeLink.addClass('active');
    <?php else: ?>
      const ACTIVE_LINK = null;
    <?php endif; ?>
  </script>
</body>
</html>
