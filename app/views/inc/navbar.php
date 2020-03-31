    <nav>
      <a href="/"><?php echo SITENAME; ?></a>
      <ul>
<?php if(!isset($_SESSION['user_id'])): ?>
        <li><a href="<?php echo URLROOT; ?>/users/signup">Sign Up</a></li>
        <li><a href="<?php echo URLROOT; ?>/users/login">Login</a></li>
<?php else: ?>
        <li><a href="#">Notifications</a></li>
        <li><a href="#">Post</a></li>
        <li><a href="<?php echo URLROOT; ?>/users/logout">Logout</a></li>
<?php endif; ?>
      </ul>
    </nav>
