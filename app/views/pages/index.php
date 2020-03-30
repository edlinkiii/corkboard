<?php require APPROOT . '/views/inc/header.php' ?>
<section>
  <!--
  <header>
    <h2>The Posts</h2>
    <p>This is where the stuff happens.</p>
  </header>
  -->
<?php foreach($data['posts'] as $post): ?>
  <aside>
    <h3>@<?php echo $post['user_name']; ?></h3>
    <hr />
    <p><?php echo $post['body']; ?></p>
  </aside>
<?php endforeach; ?>
</section>
<?php require APPROOT . '/views/inc/footer.php' ?>
