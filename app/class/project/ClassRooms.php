<?php

class ClassRooms
{

    private $id_classroom;
    private $id_classroom_category;
    private $id_account;
    private $title;
    private $icon;
    private $description;
    private $model;
    private $slug;
    private $is_private;
    private $is_writable;
    private $is_active;
    private $launch_time;
    private $insert_time;
    private $update_time;
    private $disable_time;
    private $classroom_exists;

    public function __construct()
    {
        try {
            $category_slug = get_request("classroom_category", true, false);
            $classroom_slug = get_request("classroom_slug", true, false);

            $text = new Text();
            $database = new Database();
            $database->query('SELECT * FROM classrooms cr LEFT JOIN classroom_categories cc on cr.id_classroom_category = cc.id_classroom_category WHERE cc.slug = ? AND cr.slug = ?');
            $database->bind(1, $category_slug);
            $database->bind(2, $classroom_slug);
            $result = $database->resultsetObject();
            if ($result && count(get_object_vars($result)) > 0) {
                $this->classroom_exists = true;
                foreach ($result as $key => $value) {
                    $this->$key = $text->set($value)->utf8()->output();
                }
            }
        } catch (Exception $exception) {
            logger($exception);
        }

    }

    public function loadById($id_classroom)
    {
        try {
            $text = new Text();
            $database = new Database();
            $database->query('SELECT * FROM classrooms cr LEFT JOIN classroom_categories cc on cr.id_classroom_category = cc.id_classroom_category WHERE cr.id_classroom = ?');
            $database->bind(1, $id_classroom);
            $result = $database->resultsetObject();
            if ($result && count(get_object_vars($result)) > 0) {
                $this->classroom_exists = true;
                foreach ($result as $key => $value) {
                    $this->$key = $text->set($value)->utf8()->output();
                }
            }
        } catch (Exception $exception) {
            logger($exception);
        }

    }


    public function navigation($output_options = false): ?string
    {
        try {
            $account = new Accounts();
            $id_account = $account->getIdAccount();
            if (!$output_options) $query = "SELECT cc.id_classroom_category, cc.category_name, cc.display_heading, cc.slug, GROUP_CONCAT(cr.id_classroom, ',', cr.title, ',', cr.icon, ',', cr.is_private, ',', cr.slug SEPARATOR ';') AS navigation FROM classroom_categories cc LEFT JOIN classrooms cr ON cr.id_classroom_category = cc.id_classroom_category WHERE (cc.is_active = 'Y' AND cr.is_active = 'Y' AND cr.is_sidebar_visible= 'Y') AND cr.id_classroom IN (SELECT id_classroom FROM accounts2classrooms WHERE id_account = ?) GROUP BY cc.id_classroom_category, cc.display_heading";
            else $query = "SELECT cc.id_classroom_category, cc.category_name, cc.display_heading, cc.slug, GROUP_CONCAT(cr.id_classroom, ',', cr.title, ',', cr.icon, ',', cr.is_private, ',', cr.slug SEPARATOR ';') AS navigation FROM classroom_categories cc LEFT JOIN classrooms cr ON cr.id_classroom_category = cc.id_classroom_category WHERE (cc.is_active = 'Y' AND cr.is_active = 'Y' AND cr.is_sidebar_visible= 'Y') AND cr.id_classroom IN (SELECT id_classroom FROM accounts2classrooms WHERE id_account = ?) AND cr.id_classroom <> 5 AND cr.is_writable = 'Y' GROUP BY cc.id_classroom_category, cc.display_heading";

            $database = new Database();
            $database->query($query);
            $database->bind(1, $id_account);
            $results = $database->resultSet();
            if ($output_options) return $this->navigation2options($results);
            if (count($results) > 0) return $this->navigation2html($results);
        } catch (Exception $exception) {
            logger($exception);
        }
        return null;
    }

    private function navigation2html(array $results): string
    {
        $last_category = "";
        $html = "";
        try {
            for ($i = 0; $i < count($results); $i++) {
                $current_category = $results[$i]['id_classroom_category'];
                $category_slug = $results[$i]['slug'];
                $category_name = $results[$i]['category_name'];
                $display_heading = $results[$i]['display_heading'];
                $navigation = $results[$i]['navigation'];

                if ($last_category !== $current_category) {
                    if ($i > 0) $html .= "</div>";
                    if ("Y" === $display_heading) $html .= "<div class=\"sidebar-heading heading-text\">" . translate($category_name) . "</div>";
                    $html .= "<div class=\"sidebar-list\">";
                }

                if (not_empty_bool($navigation)) {
                    $nav_split = explode(";", $navigation);

                    if (count($nav_split) > 0) {
                        for ($x = 0; $x < count($nav_split); $x++) {

                            $item_line = $nav_split[$x];
                            $item_line_split = explode(",", $item_line);
                            if (count($item_line_split) > 0) {
                                $id_classroom = $item_line_split[0];
                                $title = $item_line_split[1];
                                $icon = $item_line_split[2];
                                $is_private = $item_line_split[3];
                                $classroom_slug = $item_line_split[4];
                                $html .= "<a class=\"sidebar-item\" href=\"" . $this->classroom_link($category_slug, $classroom_slug) . "\">";
                                $html .= "    <div class=\"sidebar-icon-wrapper \">" . $this->icon($icon) . "</div>";
                                $html .= "    <div class=\"sidebar-text-wrapper \">" . translate($title) . "</div>";
                                $html .= "</a>";
                            }


                        }
                    }

                }

                if ($i === (count($results) - 1)) {
                    $html .= "</div>";
                }

                $last_category = $current_category;

            }
            return $html;
        } catch (Exception $exception) {
            logger($exception);
        }
        return "";
    }

    private function navigation2options(array $results): string
    {
        $last_category = "";
        $html = "";
        try {
            for ($i = 0; $i < count($results); $i++) {
                $current_category = $results[$i]['id_classroom_category'];
                $category_slug = $results[$i]['slug'];
                $category_name = $results[$i]['category_name'];
                $display_heading = $results[$i]['display_heading'];
                $navigation = $results[$i]['navigation'];

                if ($last_category !== $current_category) {
                    if ($i > 0) $html .= "</optgroup>";
                    $html .= "<optgroup value=\"" . $category_name . "\" label=\"" . $category_name . "\">";
                }

                if (not_empty_bool($navigation)) {
                    $nav_split = explode(";", $navigation);

                    if (count($nav_split) > 0) {
                        for ($x = 0; $x < count($nav_split); $x++) {

                            $item_line = $nav_split[$x];
                            $item_line_split = explode(",", $item_line);
                            if (count($item_line_split) > 0) {
                                $id_classroom = $item_line_split[0];
                                $title = $item_line_split[1];
                                $icon = $item_line_split[2];
                                $is_private = $item_line_split[3];
                                $classroom_slug = $item_line_split[4];
                                $html .= "<option " . ($id_classroom === get_request("classroom") ? "selected" : "") . " value=\"" . $id_classroom . "\">" . $title . "</option>";
                            }


                        }
                    }

                }

                if ($i === (count($results) - 1)) {
                    $html .= "</optgroup>";
                }

                $last_category = $current_category;

            }
            return $html;
        } catch (Exception $exception) {
            logger($exception);
        }
        return "";
    }


    private function icon($icon)
    {
        if (str_contains($icon, "fa")) {
            return "<i class=\"" . $icon . "\"></i>";
        }
        return $icon;
    }

    private function classroom_link($category_slug, $classroom_slug): string
    {
        $url = new URL();
        if ($classroom_slug === "index") {
            return $url->application("classroom")->page($category_slug)->output();
        }
        return $url->application("classroom")->page($category_slug . "/" . $classroom_slug)->output();
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
    public function getIdClassroomCategory()
    {
        return $this->id_classroom_category;
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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return mixed
     */
    public function getIsPrivate()
    {
        return $this->is_private === "Y";
    }

    /**
     * @return mixed
     */
    public function getIsWritable()
    {
        return $this->is_writable === "Y";
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->is_active === "Y";
    }

    /**
     * @return mixed
     */
    public function getLaunchTime()
    {
        return $this->launch_time;
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
     * @return mixed
     */
    public function getDisableTime()
    {
        return $this->disable_time;
    }

    /**
     * @return bool
     */
    public function isClassroomExists(): bool
    {
        return $this->classroom_exists;
    }


}