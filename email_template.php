<html lang="en">
<body>
<h1><?= $board->name ?></h1>
<h2><?= $board->summary ?></h2>
<?php
foreach ($board->member_action_summary as $member_id => $actions) {
    if (empty($actions)) {
        continue;
    }
?>
    <hr>
    <h3><?= $board->members[$member_id]->fullName; ?></h3>
    <img src="https://trello-avatars.s3.amazonaws.com/<?= $board->members[$member_id]->avatarHash ?>/170.png" class="avatar">
    <ul>
        <?php foreach ($actions as $action) { ?>
        <li><?= $action ?></li>
        <?php } ?>
    </ul>
<?php
}

?> 
</body>
</html>
