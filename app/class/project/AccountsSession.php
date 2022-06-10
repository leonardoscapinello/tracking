<?php

class AccountsSession
{

    private $_session_name;
    private $_app_url;
    private $_username;
    private $_password;

    public function __construct()
    {
        $env = new Env();
        $this->_session_name = $env->get("SESSION_NAME");
        $this->_app_url = $env->get("APP_URL");
    }

    private function getSession(): ?string
    {
        try {
            if (not_empty_bool($this->_session_name)) {
                return get_request($this->_session_name);
            }
        } catch (Exception $exception) {
            logger($exception);
        }
        return null;
    }

    public function username($username): AccountsSession
    {
        $this->_username = $username;
        return $this;
    }

    public function password($password): AccountsSession
    {
        $this->_password = $password;
        return $this;
    }

    public function isLogged(): int
    {
        try {
            $session_value = $this->getSession();
            if (not_empty_bool($session_value)) {
                $database = new Database();
                $database->query("SELECT id_account FROM accounts_sessions WHERE session_token = ? AND is_active = 'Y' AND is_authorized = 'Y'");
                $database->bind(1, $session_value);
                $resultSet = $database->resultSet();
                if (count($resultSet) > 0) return $resultSet[0]['id_account'];
            }
        } catch (Exception $exception) {
            logger($exception);
        }
        return 0;
    }

    public function getAccountId(): int
    {
        return $this->isLogged();
    }

    public function authenticate(): bool
    {
        try {
            $encrypt = new Encryption();
            $username = $this->_username;
            $password = $this->_password;
            $session_value = $this->getSession();
            if (!not_empty_bool($session_value) && not_empty_bool($username) && not_empty_bool($password)) {
                $password = $encrypt->Encrypt($password);
                $database = new Database();
                $database->query("SELECT id_account FROM accounts WHERE (username = ? OR email_address = ?) AND password = ? AND is_active = 'Y'");
                $database->bind(1, $username);
                $database->bind(2, $username);
                $database->bind(3, $password);
                $resultSet = $database->resultSet();
                if (count($resultSet) > 0) {
                    return $this->createSession($resultSet[0]['id_account']);
                }
            }
        } catch (Exception $exception) {
            logger($exception);
        }
        return false;
    }

    private function createSession($id_account): bool
    {
        try {
            $url = new URL();
            $text = new Text();
            $numeric = new Numeric();
            $browserDetection = new BrowserDetection();
            $database = new Database();
            $session_token = $text->random(64)->output();
            $authorization_token = $numeric->random(6)->output();
            $browser = $browserDetection->getUserAgent();
            $ip_address = $browserDetection->getIpAddress();
            $database->query("INSERT INTO accounts_sessions (id_account, session_token, authorization_token, browser, ip_address, is_active, is_authorized) VALUES (?,?,?,?,?,?,?)");
            $database->bind(1, $id_account);
            $database->bind(2, $session_token);
            $database->bind(3, $authorization_token);
            $database->bind(4, $browser);
            $database->bind(5, $ip_address);
            $database->bind(6, "Y");
            $database->bind(7, "Y");
            $database->execute();
            $lastId = $database->lastInsertId();
            if ($lastId > 0 && not_empty_bool($this->_session_name)) {
                $rootDomain = $url->getDomain($this->_app_url);
                return setcookie($this->_session_name, $session_token, (time() + (60 * 60 * 24 * 30 * 12)), '/', $rootDomain);;
            }
        } catch (Exception $exception) {
            logger($exception);
        }

        return false;

    }


}