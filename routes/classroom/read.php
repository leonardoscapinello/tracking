<?php
$id_post = $url->getId();
$post = new Posts($id_post);

if (!$post->getIdPost() > 0) {
    $url->application("authenticate")->page("login")->location();
}

$postContents = new PostsContents();
$contents = $postContents->getByPostId($post->getIdPost());
$postUser = new Accounts($post->getIdAccount());
$date = new Date();
?>


<div class="content-wrapper">
    <div class="post-new-widget">
        <div class="container">
            <div class="post-user">
                <div class="user-image">
                    <img src="<?= $postUser->getProfileImage(46); ?>" alt="<?= $postUser->getFullName() ?>"/>
                </div>
                <div class="user-details">
                    <div class="user-name"><?= $postUser->getFullName() ?></div>
                    <div class="user-tags"></div>
                </div>
                <div class="post-time">
                    <?= $date->getTimeAgo($post->getInsertTime()) ?>
                </div>
            </div>
            <div class="separator"></div>
            <div class="post-content-wrapper">
                <?php for ($i = 0; $i < count($contents); $i++) { ?>
                    <p><?= $contents[$i]['content'] ?></p>
                <?php } ?>
            </div>
            <div class="post-controls">
                <div class="post-control-item">
                    <i class="far fa-heart"></i>
                </div>
                <div class="post-control-item">
                    <i class="far fa-comment"></i>
                </div>
            </div>
        </div>
        <div class="separator"></div>
    </div>
</div>

