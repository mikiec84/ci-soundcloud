<div id="me">
    <p class="avatar">
        <img src="<?php echo $me['avatar_url']; ?>" alt="" />
    </p>
    <h2><?php echo $me['username']; ?></h2>
    <p class="fullname"><?php echo $me['full_name']; ?></p>
</div>
<div class="clear"></div>
<h3>Following (<?php echo count($following); ?>)</h3>
<ul class="peeps">
    <?php foreach ($following as $i => $follower): ?>
        <?php if ($i == 10) break; ?>
        <li>
            <p class="avatar"><img src="<?php echo $follower['avatar_url']; ?>" alt="" /></p>
            <p class="fullname"><?php echo $follower['full_name']; ?></p>
            <p class="username"><a href="<?php echo base_path(); ?>users/<?php echo $follower['permalink'] ?>"><?php echo $follower['username'] ?></a></p>
        </li>
    <?php endforeach; ?>
</ul>
<div class="clear tiny"></div>
<p>... and <?php echo count($following) - 10; ?> more people.</p>
<h3>Followers (<?php echo count($followers); ?>)</h3>
<ul class="peeps">
    <?php foreach ($followers as $i => $follower): ?>
        <?php if ($i == 10) break; ?>
        <li>
            <p class="avatar"><img src="<?php echo $follower['avatar_url']; ?>" alt="" /></p>
            <p class="fullname"><?php echo $follower['full_name']; ?></p>
            <p class="username"><a href="<?php echo base_path(); ?>users/<?php echo $follower['permalink'] ?>"><?php echo $follower['username'] ?></a></p>
        </li>
    <?php endforeach; ?>
</ul>
<div class="clear tiny"></div>
<p>... and <?php echo count($followers) - 10; ?> more people.</p>