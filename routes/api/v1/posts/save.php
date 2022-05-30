<?php

$array_request = (array) json_decode($request_body);

if(array_key_exists("id", $array_request) && array_key_exists("post_data", $array_request)){

    $postContent = new PostsContents();
    $post_data = (array) $array_request['post_data'];

    $post = new Posts($url->getId());
    $postContent->setIdPost($post->getIdPost());
    $postContent->setPostVersion($post->getVersion());
    $postContent->storeByObject($post_data);


}


?>