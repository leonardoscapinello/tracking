<?php

class Domains
{

    private $id_domain;
    private $id_account;
    private $public_key;
    private $domain;
    private $domain_token;
    private $is_active;
    private $is_verified;
    private $validation_method;
    private $verification_key;
    private $insert_time;
    private $verify_time;
    private $verification_meta_name = "james-domain";


    public function __construct()
    {
        try {
            if (get_request("app") === "dashboard") {
                $set = get_request("set");
                if (not_empty_bool($set)) {
                    $this->setDomainCookie($set);
                } else {
                    $this->checkDomainSetup();
                }
            }

            if (not_empty_bool($this->getDomainByCookie())) {
                $this->loadDomainByPublicKey($this->getDomainByCookie());
            }


        } catch (Exception $exception) {
            error_log($exception);
        }
    }

    public function setDomainCookie($public_key)
    {
        try {
            $url = new URL();
            $env = new Env();
            $rootDomain = $url->getDomain($env->get("APP_URL"));
            $cookie = setcookie("_domain", $public_key, (time() + (60 * 60 * 24 * 30 * 12)), '/', $rootDomain);
            if ($cookie && not_empty_bool(get_request("next"))) {
                $url->goToNext();
            }
        } catch (Exception $exception) {
            error_log($exception);
        }
    }


    public function checkDomainSetup()
    {
        try {
            $set = $this->getDomainByCookie();
            $url = new URL();
            $this->loadDomainByPublicKey($set);
            $verified = $this->getIsVerified();
            $active = $this->getIsActive();
            if ($active && !$verified && (get_request("module_slug") !== "setup-domain" && get_request("module_slug") !== "new-domain")) {
                $url->application("dashboard")->page("setup-domain")->setId($set)->redirect();
            }
        } catch (Exception $exception) {
            error_log($exception);
        }
    }

    public function loadDomainByPublicKey($public_key)
    {
        try {
            $text = new Text();
            $database = new Database();
            $database->query('SELECT * FROM domains WHERE public_key = ?');
            $database->bind(1, $public_key);
            $result = $database->resultsetObject();
            if ($result && count(get_object_vars($result)) > 0) {
                foreach ($result as $key => $value) {
                    $this->$key = $text->set($value)->utf8()->output();
                }
            }
        } catch (Exception $exception) {
            error_log($exception);
        }
    }

    public
    function getAllDomains()
    {
        try {
            $database = new Database();
            $account = new Accounts();
            $id_account = $account->getIdAccount();
            $database->query("SELECT * FROM domains WHERE id_account = ? AND is_active = 'Y'");
            $database->bind(1, $id_account);
            return $database->resultSet();
        } catch (Exception $exception) {
            error_log($exception);
        }
    }

    public
    function updateValidationMethod($validation_method, $public_key)
    {
        try {
            if ($validation_method === "dns" || $validation_method === "html" || $validation_method === "meta") {
                $database = new Database();
                $database->query("UPDATE domains SET validation_method = ? WHERE public_key = ?");
                $database->bind(1, $validation_method);
                $database->bind(2, $public_key);
                $database->execute();
                return true;
            }
            return false;
        } catch (Exception $exception) {
            error_log($exception);
        }
    }

    public
    function getCurrentDomain()
    {
        return get_request("_domain",);
    }

    public
    function getDomain()
    {
        return $this->domain;
    }

    public
    function getDomainByCookie()
    {
        return get_request("_domain");
    }

    public
    function setDomain($domain): Domains
    {
        $this->domain = $domain;
        return $this;
    }

    public
    function getDomainToken()
    {
        return $this->domain_token;
    }

    public
    function setDomainToken($domain_token): Domains
    {
        $this->domain_token = $domain_token;
        return $this;
    }

    public
    function getIsActive(): bool
    {
        return $this->is_active === "Y";
    }

    public
    function setIsActive($is_active): Domains
    {
        $this->is_active = $is_active;
        return $this;
    }

    public
    function getIsVerified(): bool
    {
        return $this->is_verified === "Y";
    }

    public
    function setIsVerified($is_verified): Domains
    {
        $this->is_verified = $is_verified;
        return $this;
    }

    public
    function getInsertTime()
    {
        return $this->insert_time;
    }

    public
    function getVerifyTime()
    {
        return $this->verify_time;
    }


    public
    function getPublicKey()
    {
        return $this->public_key;
    }

    public
    function getValidationMethod()
    {
        return $this->validation_method;
    }

    public
    function getVerificationKey()
    {
        return $this->verification_key;
    }


    public
    function createToken(): string
    {
        $text = new Text();
        return $text->random(6)->uppercase()->output();
    }

    public
    function createPublicKey(): string
    {
        $text = new Text();
        return $text->uuid();
    }

    public
    function getVerificationMetaName(): string
    {
        return $this->verification_meta_name;
    }


    public
    function createVerificationKey(): string
    {
        $text = new Text();
        return $text->random(30)->output();
    }


    public
    function store(): ?string
    {
        try {
            $session = new AccountsSession();
            $token = $this->createToken();
            $public_key = $this->createPublicKey();
            $verification_key = $this->createVerificationKey();
            if (not_empty_bool($this->getDomain())) {
                $database = new Database();
                $database->query("INSERT INTO domains (id_account, domain, domain_token, public_key, is_active, is_verified, verification_key) VALUES (?,?,?,?,?,?,?)");
                $database->bind(1, $session->getAccountId());
                $database->bind(2, $this->getDomain());
                $database->bind(3, $token);
                $database->bind(4, $public_key);
                $database->bind(5, "Y");
                $database->bind(6, "N");
                $database->bind(7, $verification_key);
                $database->execute();
                $lid = $database->lastInsertId();
                $this->setDomainCookie($public_key);
                if (not_empty_bool($lid)) return $public_key;
            }
        } catch (Exception $exception) {
            error_log($exception);
        }
        return null;
    }


}