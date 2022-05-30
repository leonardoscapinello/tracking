<?php

class Feed
{

    public function __construct()
    {

    }

    public function get()
    {
        try {

            $classroom_filter = not_empty_bool(get_request("classroom")) ? " AND po.id_classroom = '" . get_request("classroom") . "' " : "";

            $account = new Accounts();
            $id_account = $account->getIdAccount();
            $query = "SELECT po.id_post, po.id_classroom, po.id_account, po.post_title, po.share_link, po.post_type, po.insert_time FROM posts po  WHERE po.id_classroom IN (SELECT id_classroom FROM accounts2classrooms WHERE id_account = ?) " . $classroom_filter . " ORDER BY po.insert_time DESC";
            $database = new Database();
            $database->query($query);
            $database->bind(1, $id_account);
            $results = $database->resultSet();
            if (count($results) > 0) return $this->feed2json($results);
        } catch (Exception $exception) {
            logger($exception);
        }
        return $this->feed2json(array());
    }

    public function getContents($id_post): array
    {
        $contents = array();
        $posts = new Posts();
        try {
            $database = new Database();
            $database->query("SELECT content FROM posts_contents WHERE id_post = ?");
            $database->bind(1, $id_post);
            $results = $database->resultSet();
            if (count($results) > 0) {
                for ($i = 0; $i < count($results); $i++) {
                    $contents[] = array("paragraph" => $results[$i]['content']);
                }
            }
        } catch (Exception $exception) {
            logger($exception);
        }
        return $contents;
    }

    private function feed2json($results)
    {
        $url = new URL();
        $date = new Date();
        $global = array();
        $posts = new Posts();
        for ($i = 0; $i < count($results); $i++) {

            $account = new Accounts($results[$i]['id_account']);

            $complete_url = $posts->getPermanentLinkById($results[$i]['id_post']);


            $global[] = array(
                "id" => $results[$i]['id_post'],
                "type" => $results[$i]['post_type'],
                "title" => $results[$i]['post_title'],
                "date" => $date->set($results[$i]['insert_time'])->format("d/m/Y H:i")->output(),
                "time" => $date->getTimeAgo($results[$i]['insert_time']),
                "links" => [
                    "read" => $complete_url,
                    "share" => $url->application("post-share")->page($results[$i]['share_link'])->output(),
                ],
                "author" => [
                    "first_name" => $account->getFirstName(),
                    "last_name" => $account->getLastName(),
                    "profile_image" => $account->getProfileImage(),
                ],
                "contents" => $this->getContents($results[$i]['id_post']),
                "classroom" => [
                    "id" => $results[$i]['id_classroom'],
                ],
            );


        }
        return json_encode($global);
    }

}