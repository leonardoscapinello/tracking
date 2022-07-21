<?php

class Accounts
{


    private $id_account;
    private $id_plan;
    private $first_name;
    private $last_name;
    private $username;
    private $email_address;
    private $password;
    private $mobile_phone;
    private $role;
    private $profile_image;
    private $company_name;
    private $version;
    private $insert_time;
    private $update_time;
    private $is_active;
    private $force_password;

    public function __construct($id_account = 0)
    {
        try {
            $session = new AccountsSession();
            $numeric = new Numeric();
            $text = new Text();

            $is_logged = $session->isLogged();
            if (not_empty($id_account) && $numeric->isNumber($id_account) && $id_account > 0) {
                $is_logged = $id_account;
            }
            if (not_empty($is_logged) && $numeric->isNumber($is_logged) && $is_logged > 0) {

                $database = new Database();
                $database->query('SELECT * FROM accounts WHERE id_account = ?');
                $database->bind(1, $is_logged);
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

    public function getIdAccount()
    {
        return $this->id_account;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmailAddress()
    {
        return $this->email_address;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function getFullName(): string
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function getMobilePhone()
    {
        return $this->mobile_phone;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getIsActive(): bool
    {
        return $this->is_active === "Y";
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getInsertTime()
    {
        return $this->insert_time;
    }

    public function getUpdateTime()
    {
        return $this->update_time;
    }

    public function getProfileImage($size = 38): string
    {
        $url = new URL();
        return $url->page("profile/drawn/" . $size . "x" . $size . "/E5F5CA/9DD052/100/2/png?text=" . $this->getFullName())->output();
    }

    public function getIdPlan()
    {
        return $this->id_plan;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getCompanyName()
    {
        return $this->company_name;
    }


    public function getForcePassword()
    {
        return $this->force_password;
    }


}