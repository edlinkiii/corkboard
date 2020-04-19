    </section>
  </main>
  <script>
    const URLROOT = '<?php echo URLROOT; ?>';
    const REACTION_URL = URLROOT + '/posts/react/';
    const ICON_CLASS = 'flaticon';
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
    const UNCOMMENTED_COLOR_CLASS = 'uncommented-gray';
    const COMMENTED_COLOR_CLASS = 'commented-blue';
  </script>
  <script src="<?php echo URLROOT; ?>/js/main.js"></script>
</body>
</html>
