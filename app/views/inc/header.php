<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/mvp.css" />
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/myui.css" />
  <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css" />
  <script src="<?php echo URLROOT; ?>/js/js-query.js"></script>
  <script src="<?php echo URLROOT; ?>/js/myui.js"></script>
  <title><?php echo SITENAME; ?></title>
</head>
<body>
  <header>
    <!--
    <h1><?php echo SITENAME; ?></h1>
    <p>Share what's happening in your world</p>
    <br>
    -->
<?php require APPROOT . '/views/inc/navbar.php' ?>
  </header>
  <hr />
  <main>
    <aside>
      <ul>
        <li><a href="<?php echo URLROOT; ?>/"><b>Home</b></a></li>
        <li><a><b>Notifications</b></a></li>
        <li><a><b>Stalking</b></a></li>
        <li><a><b>Something</b></a></li>
        <li><a href="<?php echo URLROOT; ?>/posts/add"><b>Post</b></a></li>
      </ul>
    </aside>
    <section>
