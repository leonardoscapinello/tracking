<?php

class Pixels
{

    private $id_pixel;
    private $id_domain;
    private $id_account;
    private $pixel_name;
    private $pixel_token;
    private $insert_time;
    private $activity_time;
    private $update_time;
    private $is_active;


    public function __construct($pixel_token = null)
    {
        try {
            $text = new Text();
            if (not_empty($pixel_token) && strlen($pixel_token) === 16) {
                $database = new Database();
                $database->query('SELECT * FROM pixels WHERE pixel_token = ? AND id_account = ?');
                $database->bind(1, $pixel_token);
                $database->bind(2, ACCOUNT_ID);
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


    public function getIdPixel()
    {
        return $this->id_pixel;
    }

    public function getIdDomain()
    {
        return $this->id_domain;
    }

    public function getIdAccount()
    {
        return $this->id_account;
    }

    public function getPixelName()
    {
        return $this->pixel_name;
    }

    public function getPixelToken()
    {
        return $this->pixel_token;
    }

    public function getInsertTime()
    {
        return $this->insert_time;
    }

    public function getActivityTime()
    {
        return $this->activity_time;
    }

    public function getUpdateTime()
    {
        return $this->update_time;
    }

    public function getIsActive()
    {
        return $this->is_active;
    }

    public function setPixelName($pixel_name): Pixels
    {
        $this->pixel_name = $pixel_name;
        return $this;
    }

    private function token(): string
    {
        try {
            $text = new Text();
            do {
                $token = $text->random(16)->uppercase()->output();
                $exits = $this->tokenExists($token);
            } while ($exits === true);
            return $token;
        } catch (Exception $exception) {
            error_log($exception);
        }
    }

    private function tokenExists($token)
    {
        try {
            $database = new Database();
            $database->query("SELECT id_pixel FROM pixels WHERE pixel_token = ?");
            $database->bind(1, $token);
            $result = $database->resultSet();
            if (count($result) > 0) return true;
            return false;
        } catch (Exception $exception) {
            error_log($exception);
        }
    }

    public function save(): ?string
    {
        try {

            $domains = new Domains();
            $id_domain = $domains->getIdDomain();
            $token = $this->token();
            $database = new Database();
            $database->query("INSERT INTO pixels (id_domain,id_account,pixel_name,pixel_token,is_active) VALUES (?,?,?,?,'Y')");
            $database->bind(1, $id_domain);
            $database->bind(2, ACCOUNT_ID);
            $database->bind(3, $this->getPixelName());
            $database->bind(4, $token);
            $database->execute();
            $last_id = $database->lastInsertId();
            return not_empty_bool($last_id) ? $token : null;

        } catch (Exception $exception) {
            error_log($exception);
        }
        return null;
    }

    public function getAll()
    {
        try {
            $database = new Database();
            $database->query("SELECT * FROM pixels WHERE id_account = ? AND ((is_active = 'Y') OR (is_active = 'N' AND remove_date > DATE_ADD(NOW(), INTERVAL -" . REMOVAL_DELAY . " HOUR) )) ORDER BY is_active DESC, pixel_name ASC");
            $database->bind(1, ACCOUNT_ID);
            return $database->resultSet();
        } catch (Exception $exception) {
            error_log($exception);
        }
    }

    public function disable($pixel_token = null): bool
    {
        try {
            $pixel_token = not_empty_bool($pixel_token) ? $pixel_token : $this->pixel_token;
            if (not_empty_bool($pixel_token)) {
                $database = new Database();
                $database->query("UPDATE pixels SET is_active = 'N', remove_date = CURRENT_TIMESTAMP WHERE pixel_token = ? AND id_account = ?");
                $database->bind(1, $pixel_token);
                $database->bind(2, ACCOUNT_ID);
                $database->execute();
                return true;
            }
        } catch (Exception $exception) {
            error_log($exception);
        }
        return false;
    }

    public function cancel_disable($pixel_token = null): bool
    {
        try {
            $pixel_token = not_empty_bool($pixel_token) ? $pixel_token : $this->pixel_token;
            if (not_empty_bool($pixel_token)) {
                $database = new Database();
                $database->query("UPDATE pixels SET is_active = 'Y', remove_date = NULL WHERE pixel_token = ? AND id_account = ?");
                $database->bind(1, $pixel_token);
                $database->bind(2, ACCOUNT_ID);
                $database->execute();
                return true;
            }
        } catch (Exception $exception) {
            error_log($exception);
        }
        return false;
    }

    public function getTrackFunctionName()
    {
        return "jms";
    }


}