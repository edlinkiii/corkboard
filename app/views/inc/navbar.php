    <nav>
      <a id="title" href="<?php echo URLROOT; ?>/pages/about"><?php echo SITENAME; ?></a>
      <ul>
<?php if(!isset($_SESSION['user_id'])): ?>
        <li><a href="<?php echo URLROOT; ?>/users/signup"><i class="fa fa-user-plus"></i> Sign Up</a></li>
        <li><a href="<?php echo URLROOT; ?>/users/login"><i class="fa fa-sign-in"></i> Login</a></li>
<?php else: ?>
        <!-- <li><a href="#">Notifications</a></li>
        <li><a href="<?php echo URLROOT; ?>/posts/add">Post</a></li> -->
        <li><a href="<?php echo URLROOT; ?>/users/logout"><i class="fa fa-sign-out"></i> Logout</a></li>
<?php endif; ?>
      </ul>
    </nav>
