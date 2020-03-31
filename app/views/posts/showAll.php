<?php require APPROOT . '/views/inc/header.php' ?>
<section>
<?php foreach($data['posts'] as $post): ?>
  <aside>
  <?php if($post->user_id === $_SESSION['user_id']): ?>
    <a class="edit-button" style="float: right;" href="<?php echo URLROOT; ?>/posts/edit/<?php echo $post->post_id; ?>"><b>Edit</b></a>
  <?php endif; ?>
    <h3><a href="<?php echo URLROOT; ?>/users/profile/<?php echo $post->user_name; ?>">@<?php echo $post->user_name; ?></a></h3>
    <a class='show-post-link' href="<?php echo URLROOT; ?>/posts/show/<?php echo $post->post_id; ?>"><?php echo $post->post_stamp; ?></a>
    <hr />
    <p><?php echo $post->post_body; ?></p>
  </aside>
<?php endforeach; ?>
</section>
<?php require APPROOT . '/views/inc/footer.php' ?>
