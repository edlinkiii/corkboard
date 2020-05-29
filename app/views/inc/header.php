<?php $reaction_config = Reaction::getReactionConfig(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/css/lib/mvp.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/css/lib/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/css/lib/myui.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/css/style.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo URLROOT; ?>/css/responsive.css" />
  <script src="<?php echo URLBASE; ?>/js/lib/js-query.js"></script>
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
  <main>
    <aside>
<?php require APPROOT . '/views/inc/sidebar.php' ?>
    </aside><section>
