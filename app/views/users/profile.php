<?php require APPROOT . '/views/inc/header.php' ?>

      <article>
        <strong>Name: </strong><?php echo $data->name; ?><br />
        <br />
        <strong>Birthdate: </strong><?php echo $data->birthdate; ?><br />
        <br />
        <strong>Bio: </strong><?php echo $data->bio; ?><br />
      </article>

      <!-- need to display posts from {user} here; should come with profile payload? -->
<?php require APPROOT . '/views/inc/footer.php' ?>
