<?php

$id_post = get_request("id");
$image = "";
$posts = new Posts($id_post);

if (isset($_FILES['image'])) {
    $uploader = new Uploader();
    $uploader->setDir(DIRNAME . '../../public/uploads/');
    $uploader->setExtensions(array('jpg', 'jpeg', 'png', 'gif'));  //allowed extensions list//
    $uploader->setMaxSize(.5);                          //set max file size to be allowed in MB//
    $uploader->setSequence($posts->getShareLink());

    if ($uploader->uploadFile('image')) {   //txtFile is the filebrowse element name //
        $image = $uploader->getUploadName(); //get uploaded file name, renames on upload//

    } else {
        echo $uploader->getMessage(); //get upload error message
    }
}

echo json_encode([
    "success" => 1,
    "file" => [
        "url" => $url->application("uploads")->page($image)->output()
    ]
])

?>