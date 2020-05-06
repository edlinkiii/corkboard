    </section>
  </main>
  <script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const LOGIN_URL = URLROOT+'/users/login';
    const SESSION_CHECK_URL = URLROOT + '/users/check';
    const REACTION_URL = URLROOT + '/posts/react/';
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
    const REPLY_URL = URLROOT + '/posts/reply/';
    const UNCOMMENTED_COLOR_CLASS = 'uncommented-gray';
    const UNCOMMENTED_ICON_CLASS = 'fa-comment-o';
    const COMMENTED_COLOR_CLASS = 'commented-blue';
    const COMMENTED_ICON_CLASS = 'fa-comment';
    const FAVORITES_URL = URLROOT + '/favorites';
    const FAVORITED_ICON_CLASS = 'fa-heart';
    const UNFAVORITED_ICON_CLASS = 'fa-heart-o';
    const POST_URL = URLROOT+'/posts/show/';
  </script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/1.0.0/marked.min.js"></script>
  <script src="<?php echo URLROOT; ?>/js/lib/js-query.js"></script>
  <script src="<?php echo URLROOT; ?>/js/lib/myui.js"></script>
  <script src="<?php echo URLROOT; ?>/js/reaction.js"></script>
  <script src="<?php echo URLROOT; ?>/js/reply.js"></script>
  <script src="<?php echo URLROOT; ?>/js/favorite.js"></script>
  <script src="<?php echo URLROOT; ?>/js/markdown.js"></script>
<?php if(isset($_SESSION['user_id'])): ?>
  <script src="<?php echo URLROOT; ?>/js/notification_check.js"></script>
<?php endif; ?>
<?php if(isset($_SESSION['active_link']) && $_SESSION['active_link'] != ''): ?>
  <script>
    const ACTIVE_LINK = '<?php echo $_SESSION['active_link']; ?>';
    let activeLink = $q('#sidebar a#'+ACTIVE_LINK);
    if(activeLink) activeLink.addClass('active');
  </script>
<?php endif; ?>
</body>
</html>
