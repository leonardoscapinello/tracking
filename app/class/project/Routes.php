<?php

class Routes
{

    private $_routes_path = DIRNAME . "../../routes";
    private $category_slug;
    private $route_slug;

    private $id_route;
    private $id_route_category;
    private $route_name;
    private $route_file;
    private $route_key;
    private $is_active;
    private $is_classroom;
    private $is_public;
    private $category_name;
    private $module_exists = false;

    public function __construct()
    {
        try {
            $this->category_slug = get_request("category_slug", true, false);
            $this->route_slug = get_request("route_slug", true, false);

            $text = new Text();
            $database = new Database();
            $database->query('SELECT * FROM routes ro LEFT JOIN routes_category rc ON rc.id_route_category = ro.id_route_category WHERE rc.category_slug = ? AND ro.route_slug = ?');
            $database->bind(1, $this->getCategorySlug());
            $database->bind(2, $this->getRouteSlug());
            $result = $database->resultsetObject();
            if ($result && count(get_object_vars($result)) > 0) {
                $this->module_exists = true;
                foreach ($result as $key => $value) {
                    $this->$key = $text->set($value)->utf8()->output();
                }
            }
        } catch (Exception $exception) {
            logger($exception);
        }

    }

    public function get(): ?string
    {
        if ($this->module_exists) {
            $complete_path = $this->_routes_path . "/" . $this->getCategorySlug() . "/" . $this->getRouteFile();
            if (not_empty_bool($complete_path)) {
                if (file_exists($complete_path)) return $complete_path;
            }
        }
        return null;
    }

    public function getCategorySlug()
    {
        return $this->category_slug;
    }

    public function getRouteSlug()
    {
        return $this->route_slug;
    }

    /**
     * @return mixed
     */
    public function getIdRoute()
    {
        return $this->id_route;
    }

    /**
     * @return mixed
     */
    public function getIdRouteCategory()
    {
        return $this->id_route_category;
    }

    /**
     * @return mixed
     */
    public function getRouteName()
    {
        return $this->route_name;
    }

    /**
     * @return mixed
     */
    public function getRouteFile()
    {
        return $this->route_file;
    }

    /**
     * @return mixed
     */
    public function getRouteKey()
    {
        return $this->route_key;
    }

    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->is_active === "Y";
    }

    /**
     * @return bool
     */
    public function getIsClassroom(): bool
    {
        return $this->is_classroom === "Y";
    }

    /**
     * @return bool
     */
    public function getIsPublic(): bool
    {
        return $this->is_public === "Y";
    }

    /**
     * @return mixed
     */
    public function getCategoryName()
    {
        return $this->category_name;
    }


}

?>