<?php

class Posts
{

    private $id_post;
    private $id_classroom;
    private $id_account;
    private $post_title;
    private $post_caption;
    private $video_url;
    private $share_link;
    private $insert_time;
    private $update_time;
    private $version;
    private $post_type = "complete";

    private function generateShareLink()
    {
        $text = new Text();
        $share_link = $text->random()->output() . substr(date("dmHis"), 0, 5) . "-" . $text->random(16)->output();
        return substr($share_link, 0, 32);
    }

    public function __construct($id_post = 0)
    {
        $text = new Text();
        $numeric = new Numeric();
        try {
            if (not_empty($id_post)) {
                $database = new Database();
                $database->query('SELECT * FROM posts WHERE id_post = ? OR share_link = ?');
                $database->bind(1, $id_post);
                $database->bind(2, $id_post);
                $result = $database->resultsetObject();
                if ($result && count(get_object_vars($result)) > 0) {
                    foreach ($result as $key => $value) {
                        $this->$key = $text->set($value)->utf8()->output();
                    }
                }
            }
        } catch (Exception $exception) {
            logger($exception);
        }

    }

    public function store()
    {
        $account = new Accounts();
        try {
            $database = new Database();
            $id_account = $account->getIdAccount();
            $share_link = $this->generateShareLink();
            $id_classroom = $this->getIdClassRoom();
            $post_type = $this->getPostType();
            $post_title = $this->getPostTitle();
            $post_caption = $this->getPostCaption();
            $video_url = $this->getVideoURL();
            $database->query("INSERT INTO posts (id_classroom, id_account, post_type, post_title, post_caption, video_url, share_link) VALUES (?,?,?,?,?,?, ?) ");
            $database->bind(1, $id_classroom);
            $database->bind(2, $id_account);
            $database->bind(3, $post_type);
            $database->bind(4, $post_title);
            $database->bind(5, $post_caption);
            $database->bind(6, $video_url);
            $database->bind(7, $share_link);
            $database->execute();
            $last_id = $database->lastInsertId();
            if (not_empty($last_id)) return $last_id;
        } catch (Exception $exception) {
            logger($exception);
        }
        return 0;
    }

    public function getPermanentLinkById($id_post): ?string
    {
        try {
            $database = new Database();
            $url = new URL();
            $database->query("SELECT * FROM posts po LEFT JOIN posts_contents pc ON pc.id_post = po.id_post LEFT JOIN classrooms c on c.id_classroom = po.id_classroom LEFT JOIN classroom_categories cc on cc.id_classroom_category = c.id_classroom_category WHERE po.id_post = ?");
            $database->bind(1, $id_post);
            $result = $database->resultSet();
            if (count($result) > 0) {
                $share_link = $result[0]['share_link'];
                $category_name = $result[0]['category_name'];
                $post_type = $result[0]['post_type'];
                $title = $result[0]['title'];
                $post_title = $result[0]['post_title'];
                if ($post_type === "quick-question") {
                    $slug = $url->friendly($share_link);
                } else {
                    $slug = $url->friendly($post_title);
                }

                $page = $url->friendly($category_name) . "/" . $url->friendly($title) . "/" . $id_post . "-" . $slug;
                return $url->application("read")->page($page)->output();
            }
        } catch (Exception $exception) {
            logger($exception);
        }
        return null;
    }

    public function getPermanentLinkByDetails($id_post, $share_link, $category_name, $post_type, $title, $post_title): ?string
    {
        try {
            $database = new Database();
            $url = new URL();
            $database->query("SELECT * FROM posts po LEFT JOIN posts_contents pc ON pc.id_post = po.id_post LEFT JOIN classrooms c on c.id_classroom = po.id_classroom LEFT JOIN classroom_categories cc on cc.id_classroom_category = c.id_classroom_category WHERE po.id_post = ?");
            $database->bind(1, $id_post);
            $result = $database->resultSet();
            if (count($result) > 0) {
                if ($post_type !== "quick-question") {
                    $slug = $url->friendly($post_title);
                } else {
                    $slug = $url->friendly($share_link);
                }
                $page = $url->friendly($category_name) . "/" . $url->friendly($title) . "/" . $id_post . "-" . $slug;
                return $url->application("read")->page($page)->output();
            }
        } catch (Exception $exception) {
            logger($exception);
        }
        return null;
    }


    /**
     * @return mixed
     */
    public function getIdPost()
    {
        return $this->id_post;
    }

    /**
     * @return mixed
     */
    public function getIdClassroom()
    {
        return $this->id_classroom;
    }

    /**
     * @return mixed
     */
    public function getIdAccount()
    {
        return $this->id_account;
    }

    /**
     * @return mixed
     */
    public function getPostTitle()
    {
        return $this->post_title;
    }

    /**
     * @return mixed
     */
    public function getShareLink()
    {
        return $this->share_link;
    }

    /**
     * @return mixed
     */
    public function getInsertTime()
    {
        return $this->insert_time;
    }

    /**
     * @return mixed
     */
    public function getUpdateTime()
    {
        return $this->update_time;
    }

    /**
     * @return string
     */
    public function getPostType(): string
    {
        return $this->post_type;
    }


    /**
     * @param mixed $id_classroom
     */
    public function setIdClassroom($id_classroom): Posts
    {
        $this->id_classroom = $id_classroom;
        return $this;
    }

    /**
     * @param mixed $id_account
     */
    public function setIdAccount($id_account): Posts
    {
        $this->id_account = $id_account;
        return $this;
    }

    /**
     * @param mixed $post_title
     */
    public function setPostTitle($post_title): Posts
    {
        $this->post_title = $post_title;
        return $this;
    }

    /**
     *
     * @param mixed $post_caption
     */
    public function setPostCaption($post_caption): Posts
    {
        $this->post_caption = $post_caption;
        return $this;
    }

    /**
     *
     * @param mixed $video_url
     */
    public function setVideoURL($video_url): Posts
    {
        $this->video_url = $video_url;
        return $this;
    }

    public function quick(): Posts
    {
        $this->post_type = "quick-question";
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostCaption()
    {
        return $this->post_caption;
    }

    /**
     * @return mixed
     */
    public function getVideoURL()
    {
        return $this->video_url;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }


}