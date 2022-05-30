<?php

function url_encode_string($item)
{
    $text = new Text();
    return $text->set($item)->encode()->output();
}

class URL
{

    private $_accents = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'þ', 'ÿ', 'á', 'é', 'í', 'ó', 'ú');
    private $_no_accents = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 'B', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'p', 'y', 'a', 'e', 'i', 'o', 'u');

    private $_url;
    private $_custom_url = false;
    private $_application_url;
    private $_wait = 0;

    public function __construct()
    {
        try {
            $this->_url = $this->actual();
        } catch (Exception $exception) {
            logger($exception);
        }
    }

    public function paramList(array $params): bool
    {
        foreach ($params as $key) {
            if (!$this->param($key)) {
                return false;
            }
        }
        return true;
    }

    public function param($key, $decode = true): string
    {
        $text = new Text();
        if ($this->_custom_url) {
            if (str_contains($this->_url, "?$key=") || str_contains($this->_url, "&$key=")) {
                $parts = parse_url($this->_url);
                parse_str($parts['query'], $query);
                return $query[$key];
            } else {
                return false;
            }
        } else {
            return (isset($_REQUEST[$key]) && not_empty($_REQUEST[$key])) ? ($decode ? $text->set($_REQUEST[$key])->decode()->output() : $_REQUEST[$key]) : false;
        }
    }

    public function id(): int
    {
        $number = new Numeric();
        return $number->set($this->param("id"))->integer()->output();
    }

    public function set($url)
    {
        $this->_custom_url = true;
        $this->_url = $url;
        return $this;
    }

    public function actualPage(): URL
    {
        $this->_custom_url = false;
        $this->_url = $this->actual();
        return $this;
    }

    public function application($application_base): URL
    {
        switch ($application_base) {
            case "uploads":
                $this->_application_url = "uploads/";
                break;
            case "post-share":
                $this->_application_url = "r/";
                break;
            case "edit-post":
                $this->_application_url = "classroom/post/edit/";
                break;
            case "classroom-tools":
                $this->_application_url = "classroom/";
                break;
            case "meeting":
                $this->_application_url = "meeting/";
                break;
            case "read":
                $this->_application_url = "read/";
                break;
            default:
                $this->_application_url = "";
                break;
        }

        return $this;
    }

    public function wait($seconds = 0): URL
    {
        $this->_wait = $seconds;
        return $this;
    }

    public function redirect()
    {
        try {
            $time = $this->_wait;
            if (!not_empty($time)) $time = 0;
            header("Refresh:$time;url=" . $this->_url);
        } catch (Exception $exception) {
            logger($exception);
        }
    }

    public function page($page): URL
    {
        $env = new Env();
        $this->_url = $env->get("APP_URL") . "/" . trim($this->_application_url) . trim($page);
        return $this;
    }

    /* REMOVE QUERY STRINGS */
    public function remove($specified): URL
    {
        for ($i = 0; $i < 10; $i++) {
            if (is_array($specified) && count((array)$specified) > 0) {
                foreach ($specified as $key => $value) {
                    $main = $key;
                    if (is_int($key)) $main = $value;
                    $this->_url = preg_replace('/(?:&|(\?))' . $main . '=[^&]*(?(1)&|)?/i', "$1", $this->_url);
                    $this->_url = rtrim($this->_url, '?');
                    $this->_url = rtrim($this->_url, '&');
                    $this->_url = preg_replace('/(.*)(?|&)' . $main . '=[^&]+?(&)(.*)/i', '$1$2$4', $this->_url . '&');
                    $this->_url = substr($this->_url, 0, -1);
                }
            } else {
                if (strpos($this->_url, "?") !== false) $this->_url = explode("?", $this->_url)[0];
            }
        }
        return $this;
    }

    public function removeAll(): URL
    {
        for ($i = 0; $i < 10; $i++) {
            if (is_array($specified) && count((array)$specified) > 0) {
                foreach ($specified as $key => $value) {
                    $main = $key;
                    if (is_int($key)) $main = $value;
                    $this->_url = preg_replace('/(?:&|(\?))' . $main . '=[^&]*(?(1)&|)?/i', "$1", $this->_url);
                    $this->_url = rtrim($this->_url, '?');
                    $this->_url = rtrim($this->_url, '&');
                    $this->_url = preg_replace('/(.*)(?|&)' . $main . '=[^&]+?(&)(.*)/i', '$1$2$4', $this->_url . '&');
                    $this->_url = substr($this->_url, 0, -1);
                }
            } else {
                if (strpos($this->_url, "?") !== false) $this->_url = explode("?", $this->_url)[0];
            }
        }
        return $this;
    }

    /* ADD QUERY STRINGS */
    public function public_key(): URL
    {
        $text = new Text();
        $public = [
            "public_key" => $text->random(128)->output(),
        ];
        $this->add($public);
        return $this;
    }

    /* ADD QUERY STRINGS */
    public function add($specified, $encoding = false): URL
    {
        $this->remove($specified);
        $specified = ($encoding ? array_map("url_encode_string", $specified) : $specified);
        $this->_url .= (strpos($this->_url, "?") === false) ? "?" . http_build_query($specified) : "&" . http_build_query($specified);
        return $this;
    }

    public function encode(): URL
    {
        $text = new Text();
        $this->_url = $text->set($this->_url)->encode()->output();
        return $this;
    }

    public function friendly($string): ?string
    {
        $text = new Text();
        try {
            $html = new \Html2Text\Html2Text($string);
            $value = $html->getText();
            $value = $text->set($value)->decode()->lowercase()->output();
            // Convert all dashes to hyphens
            $value = str_replace('—', '-', $value);
            $value = str_replace('‒', '-', $value);
            $value = str_replace('―', '-', $value);
            // Convert underscores and spaces to hyphens
            $value = str_replace('_', '-', $value);
            $value = str_replace(' ', '-', $value);
            $value = htmlspecialchars_decode($value);
            $value = html_entity_decode($value);
            $value = str_replace($this->_accents, $this->_no_accents, $value);
            $value = preg_replace('/[^A-Za-z0-9-]+/', '', $value);
            do {
                $value = str_replace('--', '-', $value);
            } while (mb_substr_count($value, '--') > 0);
        } catch (Exception $exception) {
            logger($exception);
        }
        return $value;
    }

    public function location(bool $use_next_when_exists = false, array $additional_params_for_next = [])
    {
        $text = new Text();
        if ($use_next_when_exists && not_empty($this->existsNext())) {
            $next_url = $text->set($this->existsNext())->decode()->output();
            if (count($additional_params_for_next) > 0) {
                $this->set($next_url);
                $this->add($additional_params_for_next);
                $this->location();
                die;
            } else {
                header("location: " . $text->set($this->existsNext())->decode()->output());
                die;
            }

        } else {
            header("location: " . $this->_url);
            die;
        }
    }


    public function post(array $data, $optional_headers = null): void
    {
        if (is_array($data)) $data = http_build_query($data);
        $params = array('http' => array(
            'method' => 'POST',
            'content' => $data
        ));
        if ($optional_headers !== null) {
            $params['http']['header'] = $optional_headers;
        }
        $ctx = stream_context_create($params);
        $fp = @fopen($this->_url, 'rb', false, $ctx);
        if (!$fp) {
            throw new Exception("Problem with $this->_url, $php_errormsg");
        }
        $response = @stream_get_contents($fp);
        if ($response === false) {
            throw new Exception("Problem reading data from $this->_url, $php_errormsg");
        }
    }

    private function existsNext(): ?string
    {
        $next = get_request("next");
        if (not_empty($next)) {
            $this->clean();
            $this->remove(["next"]);
            return $next;
        }
        return null;
    }

    public function actualAsNext(): URL
    {
        $text = new Text();
        $next = $text->set($this->actual())->encode()->output();
        $this->add(["next" => $next]);
        return $this;
    }

    private function clean()
    {
        $this->_custom_url = false;
        $this->_url = $this->actual();
        $this->_application_url = "";
    }

    public function output(): string
    {
        if ($this->param("next")) {
            $this->add(["next" => $this->param("next")]);
        }
        $url = $this->_url;
        $this->clean();
        return $url;
    }

    public function getId()
    {
        return get_request("id");
    }

    private function actual(): string
    {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    public function getDomain($url)
    {
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
            return $regs['domain'];
        }
        return false;
    }


}