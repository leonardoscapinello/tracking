<?php
$post = new Posts($url->getId());
$postContent = new PostsContents();
$contents = $postContent->getByPostId($post->getIdPost());
if (get_request("action") === "tokenize") {
    $url->application("edit-post")->page($post->getShareLink())->location();
}
?>

<div class="content-wrapper">
    <div class="post-new-widget">
        <div class="container">

            <h3 class="post-title"><?= $post->getPostTitle() ?></h3>
            <h4 class="post-caption"><?= $post->getPostCaption() ?></h4>

            <div class="post-container">
                <div id="editor"></div>
            </div>


        </div>
    </div>
</div>

<button id="save-button">Save</button>