<?php
$url = new URL();
$posts = new Posts();
$classroom = new ClassRooms();
$postsContents = new PostsContents();
$classroom_list = $classroom->navigation(true);

$id_classroom = get_request("id");
$classroom->loadById($id_classroom);

$post_content = get_request("post_content");
$title = get_request("title");
$post_caption = get_request("post_caption");
$video_url = get_request("video_url");
$format = get_request("format");

if ("quick" === $format && not_empty_bool($post_content) && not_empty_bool($id_classroom)) {
    $id_post = $posts->setIdAccount($account->getIdAccount())->setIdClassroom($id_classroom)->quick()->store();
    if ($id_post > 0) $postsContents->setIdPost($id_post)->setContent($post_content)->store();
    $page = $posts->getPermanentLinkById($id_post);
    $url->set($page)->location(true, ["highlight" => $id_post]);
} else {

    if (not_empty_bool($title)) {
        $id_post = $posts->setIdAccount($account->getIdAccount())->setIdClassroom($id_classroom)->setPostTitle($title)->setPostCaption($post_caption)->setVideoURL($video_url)->store();
        $page = $posts->getPermanentLinkById($id_post);
        $url->application("edit-post")->page($id_post)->add(["action" => "tokenize"])->location();
    }

}


?>

<div class="content-wrapper">
    <div class="post-new-widget">
        <div class="container">

            <form action="" method="post">

                <?= $fields->hidden()->name("id_classroom")->value($id_classroom)->output() ?>

                <h3><?= translate("Create a new post in %s", $classroom->getTitle()) ?></h3>

                <?= $fields->text()->label("Post title")->id("title")->name("title")->placeholder("Type post title here")->maxlength(96)->required()->output() ?>
                <?= $fields->textarea()->label("Post caption")->id("post_caption")->name("post_caption")->placeholder("Type post short legend here, with max 140 characters")->maxlength(140)->output() ?>
                <?= $fields->text()->label("Video Embed")->id("video_url")->name("video_url")->placeholder("YouTube or Vimeo URL")->maxlength(140)->output() ?>


                <?= $fields->button()->classList("btn menu button btn-primary")->value("Continue to content")->output() ?>
            </form>

        </div>
    </div>
</div>
