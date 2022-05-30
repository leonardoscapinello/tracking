<?php

class PostsContents
{

    private $id_post;
    private $id_post_content;
    private $content_token;
    private $content;
    private $type;
    private $file_url;
    private $caption;
    private $streched;
    private $with_background;
    private $with_border;
    private $json_structure;
    private $item_order;
    private $version;
    private $timestamp;
    private $insert_time;

    private $post_version;

    /**
     * @param mixed $id_post
     */
    public function setIdPost($id_post): PostsContents
    {
        $this->id_post = $id_post;
        return $this;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content): PostsContents
    {
        $this->content = $content;
        return $this;
    }

    public function cleanContent()
    {
        try {
            if (not_empty($this->getIdPost())) {
                $database = new Database();
                $database->query("UPDATE posts_contents SET is_active = 'N' WHERE id_post = ?");
                $database->bind(1, $this->getIdPost());
                $database->execute();
            }
        } catch (Exception $exception) {
            logger($exception);
        }
    }

    public function store()
    {
        try {
            if (not_empty($this->getIdPost())) {
                $database = new Database();
                $database->query("INSERT INTO posts_contents (id_post, content_token, type, content, file_url, caption, stretched, with_background, with_border, timestamp, item_order) VALUES (?,?,?,?,?,?,?,?,?,current_timestamp,?) ");
                $database->bind(1, $this->getIdPost());
                $database->bind(2, $this->getContentToken());
                $database->bind(3, $this->getType());
                $database->bind(4, $this->getContent());
                $database->bind(5, $this->getFileUrl());
                $database->bind(6, $this->getCaption());
                $database->bind(7, $this->getStreched());
                $database->bind(8, $this->getWithBackground());
                $database->bind(9, $this->getWithBorder());
                $database->bind(10, $this->getItemOrder());
                $database->execute();
                $this->reset();
            }
        } catch (Exception $exception) {
            logger($exception);
        }
    }

    private function incrementPostVersion()
    {
        try {
            if (not_empty($this->getIdPost())) {
                if (not_empty($this->getPostVersion())) {
                    $this->post_version = ($this->post_version + 1);
                    $database = new Database();
                    $database->query("UPDATE posts SET version = ? WHERE id_post = ?");
                    $database->bind(1, $this->getPostVersion());
                    $database->bind(2, $this->getIdPost());
                    $database->execute();
                    $this->version = $this->post_version;
                }
            }
        } catch (Exception $exception) {
            error_log($exception);
        }
    }

    public function storeByObject(array $post_data)
    {
        try {
            $blocks = $post_data['blocks'];

            $this->cleanContent();

            for ($i = 0; $i < count($blocks); $i++) {
                $this->setContentToken(get_object_value("id", $blocks[$i]));
                $this->setType(get_object_value("type", $blocks[$i]));
                $this->setItemOrder(($i + 1));


                if (property_exists($blocks[$i], "data")) {

                    $content_data = $blocks[$i]->data;
                    $this->setContent(get_object_value("text", $content_data));
                    $this->setCaption(get_object_value("caption", $content_data));
                    $this->setStreched(get_object_value("stretched", $content_data));
                    $this->setWithBackground(get_object_value("withBackground", $content_data));
                    $this->setWithBorder(get_object_value("withBorder", $content_data));


                    if (property_exists($content_data, "file")) {
                        $content_file = $content_data->file;
                        $this->setFileUrl(get_object_value("url", $content_file));
                    }
                }

                $this->store();


            }


        } catch (Exception $exception) {
            logger($exception);
        }
    }

    public function getByPostId(int $id_post = 0)
    {
        $numeric = new Numeric();
        try {
            if (not_empty($id_post) && $numeric->isNumber($id_post) && $id_post > 0) {
                $database = new Database();
                $database->query('SELECT * FROM posts_contents WHERE id_post = ?');
                $database->bind(1, $id_post);
                return $database->resultSet();
            }
        } catch (Exception $exception) {
            logger($exception);
        }
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
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getIdPostContent()
    {
        return $this->id_post_content;
    }

    /**
     * @param mixed $id_post_content
     * @return PostsContents
     */
    public function setIdPostContent($id_post_content)
    {
        $this->id_post_content = $id_post_content;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContentToken()
    {
        return $this->content_token;
    }

    /**
     * @param mixed $content_token
     * @return PostsContents
     */
    public function setContentToken($content_token)
    {
        $this->content_token = $content_token;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return PostsContents
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFileUrl()
    {
        return $this->file_url;
    }

    /**
     * @param mixed $file_url
     * @return PostsContents
     */
    public function setFileUrl($file_url)
    {
        $this->file_url = $file_url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStreched()
    {
        return $this->streched;
    }

    /**
     * @param mixed $streched
     * @return PostsContents
     */
    public function setStreched($streched)
    {
        $this->streched = $streched;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWithBackground()
    {
        return $this->with_background;
    }

    /**
     * @param mixed $with_background
     * @return PostsContents
     */
    public function setWithBackground($with_background)
    {
        $this->with_background = $with_background;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWithBorder()
    {
        return $this->with_border;
    }

    /**
     * @param mixed $with_border
     * @return PostsContents
     */
    public function setWithBorder($with_border)
    {
        $this->with_border = $with_border;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     * @return PostsContents
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     * @return PostsContents
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param mixed $caption
     * @return PostsContents
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getJsonStructure()
    {
        return $this->json_structure;
    }

    /**
     * @param mixed $json_structure
     * @return PostsContents
     */
    public function setJsonStructure($json_structure)
    {
        $this->json_structure = $json_structure;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItemOrder()
    {
        return $this->item_order;
    }

    /**
     * @param mixed $item_order
     * @return PostsContents
     */
    public function setItemOrder($item_order)
    {
        $this->item_order = $item_order;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostVersion()
    {
        return $this->post_version;
    }

    /**
     * @param mixed $post_version
     * @return PostsContents
     */
    public function setPostVersion($post_version)
    {
        $this->post_version = $post_version;
        return $this;
    }


    private function reset()
    {
        $this->id_post_content = "";
        $this->content_token = "";
        $this->content = "";
        $this->type = "";
        $this->file_url = "";
        $this->caption = "";
        $this->streched = "";
        $this->with_background = "";
        $this->with_border = "";
        $this->json_structure = "";
        $this->version = "";
        $this->timestamp = "";
        $this->insert_time = "";
    }


}