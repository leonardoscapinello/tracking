<?php

use enshrined\svgSanitize\Sanitizer;

class StaticFilesImage
{

    private $_image;
    private $_image_path;
    private $_image_size;
    private $_image_type;
    private $_image_width = 0;
    private $_image_height = 0;
    private $_new_width = 0;
    private $_new_height = 0;
    private $_new_image;
    private $_new_image_type;
    private $_compression = 75;
    private $_path = DIRNAME . "../../static/img/";
    private $_render_path = DIRNAME . "../../public/img/";
    private $_description;
    private $_is_svg;
    private $_svg_content;
    private $_class;
    private $_exists = false;
    private $_shadow = false;

    public function __construct($file)
    {
        $file = $this->_path . $file;
        if (not_empty($file) && file_exists($file)) {
            $this->_image_path = $file;
            $output = $this->filename();
            if (not_empty($output)) {
                if (file_exists($output)) return $this;
                $this->_exists = true;
                $extension = pathinfo($file)['extension'];
                if ($extension !== "svg") {
                    $this->_image_size = getimagesize($file);
                    $this->_image_type = $this->_image_size[2];
                    $this->_image_path = $file;
                    if ($this->_image_type === IMAGETYPE_JPEG) {
                        $this->_image = imagecreatefromjpeg($this->_image_path);
                    } elseif ($this->_image_type === IMAGETYPE_GIF) {
                        $this->_image = imagecreatefromgif($this->_image_path);
                    } elseif ($this->_image_type === IMAGETYPE_PNG) {
                        $this->_image = imagecreatefrompng($this->_image_path);
                    }
                    $this->_image_width = imagesx($this->_image);
                    $this->_image_height = imagesy($this->_image);
                    $this->_new_width = $this->_image_width;
                    $this->_new_height = $this->_image_height;
                    $this->_new_image_type = $this->_image_type;
                    $this->resize();
                } else {
                    $this->_is_svg = true;
                    $this->_image_path = $file;
                    $this->_svg_content = file_get_contents($this->_image_path);
                }
            }
        }
    }


    public function clean()
    {
        $_image = null;
        $_image_path = null;
        $_image_size = null;
        $_image_type = null;
        $_image_width = 0;
        $_image_height = 0;
        $_new_width = 0;
        $_new_height = 0;
        $_new_image = null;
        $_new_image_type = null;
        $_compression = 75;
        $_custom_path = "render/";
        $_description = null;
        $_is_svg = false;
        $_svg_content = null;
        $_class = null;
    }

    public function scale(int $scale): StaticFilesImage
    {
        $this->_new_width = ($this->_image_width * $scale / 100);
        $this->_new_height = ($this->_image_height * $scale / 100);
        $this->resize();
        return $this;
    }

    public function size(int $width = 0, int $height = 0): StaticFilesImage
    {
        $this->_new_width = ($width > 0 ? $width : $this->_image_width);
        $this->_new_height = ($height > 0 ? $height : $this->_image_height);

        $this->resize();
        return $this;
    }

    /*
     * $direction = W or H (ratio by Width) or (ratio by Height)
     * $ratio_size = width or height wanted
     */
    public function ratio(int $ratio_size, $direction = "W"): StaticFilesImage
    {
        if ($this->_exists) {
            if ($direction === "H") {
                $ratio = $ratio_size / $this->_image_height;
                $width = $this->_image_width * $ratio;
                $this->_new_width = $width;
                $this->_new_height = $ratio_size;
            } else {
                $ratio = $ratio_size / $this->_image_width;
                $height = $this->_image_height * $ratio;
                $this->_new_width = $ratio_size;
                $this->_new_height = $height;
            }
            $this->resize();
        }
        return $this;
    }

    public function compression(int $compression = 75): StaticFilesImage
    {
        $this->_compression = $compression;
        return $this;
    }


    public function png(): StaticFilesImage
    {
        $this->_new_image_type = IMAGETYPE_PNG;
        return $this;
    }

    public function jpeg(): StaticFilesImage
    {
        $this->_new_image_type = IMAGETYPE_JPEG;
        return $this;
    }

    public function gif(): StaticFilesImage
    {
        $this->_new_image_type = IMAGETYPE_GIF;
        return $this;
    }

    public function webp(): StaticFilesImage
    {
        $browser = new BrowserDetection();
        $browser_allowed = (strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false || strpos($_SERVER['HTTP_USER_AGENT'], ' Chrome/') !== false);
        $is_mobile = $browser->isMobile();
        if ($browser_allowed && !$is_mobile) {
            $this->_new_image_type = IMAGETYPE_WEBP;
        }
        return $this;
    }

    public function minify(): StaticFilesImage
    {
        if (!$this->_is_svg) return $this;
        $sanitizer = new Sanitizer();
        $sanitizer->minify(true);
        $sanitizer->removeRemoteReferences(true);
        $this->_svg_content = $sanitizer->sanitize($this->_svg_content);
        return $this;
    }

    public function inline(): string
    {
        $env = new Env();
        if (!$this->_is_svg) return $env->get("APP_URL") . "/img/" . $this->filename();
        return $env->get("APP_URL") . "/img/" . $this->filename();
    }

    public function save(): StaticFilesImage
    {
        if ($this->_exists) {

            $filename = $this->filename();
            $save_path = $this->_render_path . $filename;
            if ($this->_new_image_type === IMAGETYPE_JPEG) {
                imagejpeg($this->_new_image, $save_path, $this->_compression);
            } elseif ($this->_new_image_type === IMAGETYPE_GIF) {
                imagegif($this->_new_image, $save_path);
            } elseif ($this->_new_image_type === IMAGETYPE_PNG) {
                imagepng($this->_new_image, $save_path);
            } elseif ($this->_new_image_type === IMAGETYPE_WEBP) {
                imagewebp($this->_new_image, $save_path);
            }

        }
        return $this;
    }

    public function description($description): StaticFilesImage
    {
        $this->_description = $description;
        return $this;
    }

    public function html(): string
    {
        $env = new Env();
        $filename = $this->filename();
        $src = $env->get("APP_URL") . "/img/" . $filename;
        $alt = not_empty($this->_description) ? "alt=\"" . $this->_description . "\"" : "";
        $class = not_empty($this->_class) ? "class=\"" . $this->_class . "\"" : "";
        $shadow_class = ($this->_shadow) ? "class=\"shadow-effect\"" : "";

        if ($this->_is_svg) return $this->_svg_content;

        if ($this->_shadow) return sprintf("<figure %s><img src=\"%s\" %s %s><img src=\"%s\"></figure>", $shadow_class, $src, $alt, $class, $src);
        return sprintf("<figure><img src=\"%s\" %s %s></figure>", $src, $alt, $class);

    }

    public function classList($class): StaticFilesImage
    {
        $this->_class = $class;
        return $this;
    }

    private function resize(): void
    {
        if ($this->_image_type === IMAGETYPE_WEBP) {
            imagealphablending($this->_image, false);
            imagesavealpha($this->_image, true);
            $new_image = imagecreatetruecolor($this->_new_width, $this->_new_height);
            imagefill($new_image, 0, 0, 0x7fff0000);

        } else if ($this->_image_type === IMAGETYPE_PNG) {
            $new_image = imagecreatetruecolor($this->_new_width, $this->_new_height);
            imagealphablending($new_image, false);
            imagesavealpha($new_image, true);
            $transparent = imagecolorallocatealpha($new_image, 255, 255, 255, 127);
            imagefilledrectangle($new_image, 0, 0, $this->_new_width, $this->_new_height, $transparent);

        } else {
            $new_image = imagecreatetruecolor($this->_new_width, $this->_new_height);
        }
        imagecopyresampled($new_image, $this->_image, 0, 0, 0, 0, $this->_new_width, $this->_new_height, $this->_image_width, $this->_image_height);
        $this->_new_image = $new_image;
    }

    private function filename(): ?string
    {
        if (not_empty($this->_image_path)) {
            $path_parts = pathinfo($this->_image_path);
            $extension = (array_key_exists('extension', $path_parts)) ? $path_parts['extension'] : "png";
            if ($extension === "svg") return $path_parts['basename'];
            $size_key = md5($this->_new_width . "-" . $this->_new_height);
            $path_key = md5($this->_path);
            if ($this->_new_image_type === IMAGETYPE_JPEG) $extension = "jpeg";
            if ($this->_new_image_type === IMAGETYPE_GIF) $extension = "gif";
            if ($this->_new_image_type === IMAGETYPE_PNG) $extension = "png";
            if ($this->_new_image_type === IMAGETYPE_WEBP) $extension = "webp";
            return $path_parts['filename'] . "_" . $size_key . "_" . $path_key . ".$extension";
        }
        return null;
    }

    public function shadow(): StaticFilesImage
    {
        $this->_shadow = true;
        return $this;
    }

}
