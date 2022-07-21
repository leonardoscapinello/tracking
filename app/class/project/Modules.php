<?php

class Modules
{

    private $id_modules;
    private $id_modules_category;
    private $id_account;
    private $title;
    private $icon;
    private $description;
    private $model;
    private $slug;
    private $load_file;
    private $load_script;
    private $is_private;
    private $is_writable;
    private $is_sidebar_visible;
    private $is_active;
    private $is_dashboard;
    private $is_full_width;
    private $is_domain_required;
    private $launch_time;
    private $insert_time;
    private $update_time;
    private $disable_time;
    private $modules_exists;

    private $category_name;
    private $category_slug;
    private $display_heading;

    public function __construct()
    {
        try {
            $module_category = get_request("module_category", true, false);
            $module_slug = get_request("module_slug", true, false);
            $text = new Text();
            $database = new Database();
            $database->query('SELECT cr.id_modules, cr.id_modules_category, cr.id_account, cr.title, cr.icon, cr.description, cr.model, cr.slug, cr.load_file, cr.is_private, cr.is_writable, cr.is_sidebar_visible, cr.is_active, cr.launch_time, cr.insert_time, cr.update_time, cr.disable_time, cc.category_name, cc.slug AS \'category_slug\', cc.display_heading, cr.is_dashboard, cr.is_full_width, cr.is_domain_required, cr.load_script FROM modules cr LEFT JOIN modules_categories cc on cr.id_modules_category = cc.id_modules_category WHERE cr.slug = ?');
            $database->bind(1, $module_slug);
            $result = $database->resultsetObject();
            if ($result && count(get_object_vars($result)) > 0) {
                $this->modules_exists = true;
                foreach ($result as $key => $value) {
                    $this->$key = $text->set($value)->utf8()->output();
                }
            }
        } catch (Exception $exception) {
            logger($exception);
        }

    }


    public function loadById($id_modules)
    {
        try {
            $text = new Text();
            $database = new Database();
            $database->query('SELECT * FROM modules cr LEFT JOIN modules_categories cc on cr.id_modules_category = cc.id_modules_category WHERE cr.id_modules = ?');
            $database->bind(1, $id_modules);
            $result = $database->resultsetObject();
            if ($result && count(get_object_vars($result)) > 0) {
                $this->modules_exists = true;
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
            if (!$output_options) $query = "SELECT cc.id_modules_category, cc.category_name, cc.display_heading, cc.slug, GROUP_CONCAT(cr.id_modules, ',', cr.title, ',', cr.icon, ',', cr.is_private, ',', cr.slug SEPARATOR ';') AS navigation FROM modules_categories cc LEFT JOIN modules cr ON cr.id_modules_category = cc.id_modules_category WHERE (cc.is_active = 'Y' AND cr.is_active = 'Y' AND cr.is_sidebar_visible= 'Y') AND cr.id_modules IN (SELECT id_modules FROM accounts2modules WHERE id_account = ?) GROUP BY cc.id_modules_category, cc.display_heading";
            else $query = "SELECT cc.id_modules_category, cc.category_name, cc.display_heading, cc.slug, GROUP_CONCAT(cr.id_modules, ',', cr.title, ',', cr.icon, ',', cr.is_private, ',', cr.slug SEPARATOR ';') AS navigation FROM modules_categories cc LEFT JOIN modules cr ON cr.id_modules_category = cc.id_modules_category WHERE (cc.is_active = 'Y' AND cr.is_active = 'Y' AND cr.is_sidebar_visible= 'Y') AND cr.id_modules IN (SELECT id_modules FROM accounts2modules WHERE id_account = ?) AND cr.id_modules <> 5 AND cr.is_writable = 'Y' GROUP BY cc.id_modules_category, cc.display_heading";

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
                $current_category = $results[$i]['id_modules_category'];
                $category_slug = $results[$i]['slug'];
                $category_name = $results[$i]['category_name'];
                $display_heading = $results[$i]['display_heading'];
                $navigation = $results[$i]['navigation'];

                if ($current_category !== $last_category) {

                    $html .= "<ul class=\"nav bg\">";
                    if ("Y" === $display_heading) {
                        $html .= "<li class=\"nav-header hidden-folded\"><span class=\"text-muted\">" . strtoupper($category_name) . "</span></li>";
                    }

                    if (not_empty_bool($navigation)) {
                        $nav_split = explode(";", $navigation);
                        if (count($nav_split) > 0) {
                            for ($x = 0; $x < count($nav_split); $x++) {

                                $item_line = $nav_split[$x];
                                $item_line_split = explode(",", $item_line);
                                if (count($item_line_split) > 0) {
                                    $id_modules = $item_line_split[0];
                                    $title = $item_line_split[1];
                                    $icon = $item_line_split[2];
                                    $is_private = $item_line_split[3];
                                    $modules_slug = $item_line_split[4];

                                    $html .= "<li>";
                                    $html .= "    <a href=\"" . $this->getModuleLinkBySlug($category_slug, $modules_slug) . "\">";
                                    $html .= "        <span class=\"nav-icon text-primary\"><i data-feather=\"" . $icon . "\"></i></span>";
                                    $html .= "        <span class=\"nav-text\">" . $title . "</span>";
                                    $html .= "    </a>";
                                    $html .= "</li>";


                                }


                            }
                        }
                    }


                }


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
                $current_category = $results[$i]['id_modules_category'];
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
                                $id_modules = $item_line_split[0];
                                $title = $item_line_split[1];
                                $icon = $item_line_split[2];
                                $is_private = $item_line_split[3];
                                $modules_slug = $item_line_split[4];
                                $html .= "<option " . ($id_modules === get_request("modules") ? "selected" : "") . " value=\"" . $id_modules . "\">" . $title . "</option>";
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


    private function getModuleLinkBySlug($category_slug, $modules_slug): string
    {
        $url = new URL();
        //if (!not_empty_bool($category_slug)) return $url->application("dashboard")->page($modules_slug)->output();
        //return $url->application("dashboard")->page($category_slug . "/" . $modules_slug)->output();
        return $url->application("dashboard")->page($modules_slug)->output();
    }

    public function getLoadScript(): ?string
    {
        return $this->load_script;
    }


    public function getIdModules()
    {
        return $this->id_modules;
    }


    public function getIdModulesCategory()
    {
        return $this->id_modules_category;
    }


    public function getIdAccount()
    {
        return $this->id_account;
    }


    public function getTitle()
    {
        return $this->title;
    }


    public function getIcon()
    {
        return $this->icon;
    }


    public function getDescription()
    {
        return $this->description;
    }


    public function getModel()
    {
        return $this->model;
    }


    public function getSlug()
    {
        return $this->slug;
    }


    public function getLoadFile()
    {
        return $this->load_file;
    }


    public function isPrivate(): bool
    {
        return $this->is_private == "Y";
    }


    public function isWritable(): bool
    {
        return $this->is_writable == "Y";
    }


    public function isDomainRequired(): bool
    {
        return $this->is_domain_required == "Y";
    }


    public function isSidebarVisible(): bool
    {
        return $this->is_sidebar_visible == "Y";
    }


    public function isActive(): bool
    {
        return $this->is_active == "Y";
    }


    public function isDashboard(): bool
    {
        return $this->is_dashboard == "Y";
    }


    public function isFullWidth(): bool
    {
        return $this->is_full_width == "Y";
    }


    public function getLaunchTime()
    {
        return $this->launch_time;
    }

    public function getInsertTime()
    {
        return $this->insert_time;
    }


    public function getUpdateTime()
    {
        return $this->update_time;
    }


    public function getDisableTime()
    {
        return $this->disable_time;
    }


    public function isModulesExists(): bool
    {
        return $this->modules_exists == "Y";
    }

    public function getContent(): ?string
    {
        try {
            if ($this->isModulesExists()) {
                $file = DIRNAME . "../../routes/" . $this->getCategorySlug() . "/" . $this->getLoadFile();
                if (not_empty($file) && file_exists($file)) return $file;
            } else {
                //echo 'Module not exists';
            }
        } catch (Exception $exception) {
            error_log($exception);
        }
        return null;
    }


    public function getCategoryName()
    {
        return $this->category_name;
    }

    public function getCategorySlug()
    {
        return $this->category_slug;
    }

    public function getDisplayHeading()
    {
        return $this->display_heading;
    }


}