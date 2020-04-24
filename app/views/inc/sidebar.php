      <ul id='sidebar'>
        <li><a id="all" href="<?php echo URLROOT; ?>/"><b><i class="fa fa-globe"></i> All</b></a></li>
        <li><a id="notifications"><b><i class="fa fa-bell"></i> Notifications</b></a></li>
        <li><a id="favorites" href="<?php echo URLROOT; ?>/favorites"><b><i class="fa fa-heart"></i> Favorites</b></a></li>
        <li><a id="stalking" href="<?php echo URLROOT; ?>/posts/stalk"><b><i class="fa fa-binoculars"></i> Stalking</b></a></li>
        <li><a id="my_profile" href="<?php echo URLROOT; ?>/users/profile"><b><i class="fa fa-user"></i> My Profile</b></a></li>
        <li><a id="add_post" href="<?php echo URLROOT; ?>/posts/add"><b><i class="fa fa-pencil"></i> Add Post</b></a></li>
      </ul>
      <?php if(isset($_SESSION['active_link'])): ?>
      <script>
        let activeLink = $q('#sidebar a#<?php echo $_SESSION['active_link']; ?>');
        if(activeLink) activeLink.addClass('active');
      </script>
      <?php endif; ?>
