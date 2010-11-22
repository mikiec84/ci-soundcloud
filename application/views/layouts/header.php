<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php echo (isset($title)) ? $title . ' | ' : ''; ?>SoundCloud PHP API Wrapper Demo</title>
    <link rel="stylesheet" href="<?php echo base_path(); ?>public/stylesheets/reset.css" />
    <link rel="stylesheet" href="<?php echo base_path(); ?>public/stylesheets/style.css" />
</head>
<body>
    <?php if ($user): ?>
        <div id="current-user">
            <p>Logged in as <a href="<?php echo base_path(); ?>users/me"><?php echo $user['username']; ?></a></p>
        </div>
        <a id="disconnect" href="<?php echo base_path(); ?>sessions/disconnect">Disconnect</a>
    <?php endif; ?>
    <div class="space"></div>
    <div id="content">
        <a class="center" href="<?php echo base_path(); ?>">
            <img src="<?php echo base_path(); ?>public/images/soundcloud.png" alt="" />
        </a>
        <h1>SoundCloud PHP API Wrapper Demo</h1>