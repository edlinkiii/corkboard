<?php require APPROOT . '/views/inc/header.php' ?>
<?php foreach($data['posts'] as $post): ?>
<article>
  <h3>@<?php echo $post['user_name']; ?></h3>
  <hr />
  <p><?php echo $post['body']; ?></p>
</article>
<?php endforeach; ?>
<?php require APPROOT . '/views/inc/footer.php' ?>
