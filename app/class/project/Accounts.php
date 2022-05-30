<?php

class Accounts
{


    private $id_account;
    private $username;
    private $email_address;
    private $first_name;
    private $last_name;
    private $mobile_phone;
    private $password;
    private $is_active;
    private $version;
    private $insert_time;
    private $update_time;
    private $profile_image;

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
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getEmailAddress()
    {
        return $this->email_address;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->first_name . " " . $this->last_name;
    }

    /**
     * @return mixed
     */
    public function getMobilePhone()
    {
        return $this->mobile_phone;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
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
    public function getVersion()
    {
        return $this->version;
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
    public function getProfileImage($size = 38)
    {
        $url = new URL();
        return $url->page("profile/drawn/" . $size . "x" . $size . "/E5F5CA/9DD052/100/2/png?text=" . $this->getFullName())->output();
    }


}