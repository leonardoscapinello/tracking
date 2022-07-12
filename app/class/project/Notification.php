<?php

use GuzzleHttp\Client;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Notification
{

    private $id_notification;
    private $id_account;
    private $id_instance;
    private $id_zaap;
    private $id_message;
    private $message_token;
    private $subject;
    private $message;
    private $preheader;
    private $image;
    private $image_type;
    private $audio;
    private $document;
    private $message_link;
    private $submit_type;
    private $is_submit;
    private $is_active;
    private $is_buttons;
    private $insert_time;
    private $submit_time;
    private $schedule_time;

    private $_paragraph;
    private $_buttons = [];


    public function type($type): Notification
    {
        if ($type === "dashboard" || $type === "email" || $type === "whatsapp" || $type === "sms") $this->submit_type = strtolower($type);
        else $this->submit_type = "dashboard";
        return $this;
    }

    public function image_type($type): Notification
    {
        $this->image_type = (strtolower($type) === "sticker" ? "sticker" : "image");
        return $this;
    }

    public function token(): string
    {
        $text = new Text();
        return $text->uuid();
    }

    public function image($file): Notification
    {
        try {
            if ($this->submit_type === "whatsapp") {
                $path = DIRNAME . "../../public/notifications/whatsapp/" . $file;
                if (file_exists($file)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $this->image = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            }
        } catch (Exception $exception) {
            error_log($exception);
        }
        return $this;
    }

    public function audio($file): Notification
    {
        try {
            if ($this->submit_type === "whatsapp") {
                $path = DIRNAME . "../../public/notifications/whatsapp/" . $file;
                if (file_exists($file)) {
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $this->audio = 'data:audio/' . $type . ';base64,' . base64_encode($data);
                }
            }
        } catch (Exception $exception) {
            error_log($exception);
        }
        return $this;
    }

    public function p($text): Notification
    {
        if ($this->submit_type === "email") $this->_paragraph .= "<p>" . $text . "</p>";
        if ($this->submit_type === "dashboard") $this->_paragraph .= "<p>" . $text . "</p>";
        if ($this->submit_type === "whatsapp") $this->_paragraph .= (not_empty_bool($this->_paragraph) ? PHP_EOL . $text : $text);
        if ($this->submit_type === "sms") $this->_paragraph .= (not_empty_bool($this->_paragraph) ? PHP_EOL . $text : $text);
        return $this;
    }

    public function br(): Notification
    {
        if ($this->submit_type === "email") $this->_paragraph .= "<br>";
        if ($this->submit_type === "dashboard") $this->_paragraph .= "<br>";
        if ($this->submit_type === "whatsapp") $this->_paragraph .= PHP_EOL;
        if ($this->submit_type === "sms") $this->_paragraph .= PHP_EOL;
        return $this;
    }

    public function button($text, $url, $action = "URL"): Notification
    {
        if ($this->submit_type === "whatsapp") {
            $this->is_buttons = "Y";
            $this->_buttons[] = [
                "text" => $text,
                "url" => $url,
                "action" => (strtoupper($action) === "CALL" || strtoupper($action) === "URL" || strtoupper($action) === "REPLY") ? strtoupper($action) : "URL"
            ];
        } elseif ($this->submit_type === "email" || $this->submit_type === "dashboard") {
            $this->_paragraph .= "<p><a href=\"" . $url . "\" class=\"button button_notification\">" . $text . "</a></p>";
        } else {
            $this->_paragraph .= $url;
        }
        return $this;
    }

    public function subject($subject): Notification
    {
        $this->subject = translate($subject);
        return $this;
    }

    public function preheader($preheader): Notification
    {
        $this->preheader = $preheader;
        return $this;
    }

    public function schedule($baseTime): Notification
    {
        if (not_empty_bool($baseTime)) {
            $this->schedule_time = date("Y-m-d H:i:s", strtotime($baseTime));
        }
        return $this;
    }

    public function message_link($message_link): Notification
    {
        if ($this->submit_type === "whatsapp") {
            $this->message_link = $message_link;
        }
        return $this;
    }

    public function getInstanceId(): int
    {
        try {
            $database = new Database();
            $database->query("SELECT * FROM `notifications_instances` WHERE is_active = 'Y' ORDER BY RAND() LIMIT 1");
            $resultset = $database->resultSet();
            if (count($resultset) > 0) {
                return (int)$resultset[0]['id_notifications_instance'];
            }
        } catch (Exception $exception) {
            error_log($exception);
        }
        return 1;
    }


    private function sanitizeMessage()
    {
        $env = new Env();
        if ($this->submit_type === "email") {
            $template = file_get_contents(DIRNAME . "../../app/notifications/email-default.html");
            $template = str_replace("{{logo}}", '<img src="' . DIRNAME . '../../app/public/notifications/email/twj.png" alt="' . $env->get('APP_NAME') . '">', $template);
            $template = str_replace("{{preheader}}", $this->preheader, $template);
            $template = str_replace("{{content}}", $this->_paragraph, $template);
            $template = str_replace("{{year}}", date("Y"), $template);
            $template = str_replace("{{message_id}}", $this->message_token, $template);
            $template = str_replace("{{company_name}}", $env->get('APP_NAME'), $template);
            $search = array(
                '/\>[^\S ]+/s',     // strip whitespaces after tags, except space
                '/[^\S ]+\</s',     // strip whitespaces before tags, except space
                '/(\s)+/s',         // shorten multiple whitespace sequences
                '/<!--(.|\s)*?-->/' // Remove HTML comments
            );
            $replace = array(
                '>',
                '<',
                '\\1',
                ''
            );
            $this->message = preg_replace($search, $replace, $template);
        } else {
            $this->message = $this->_paragraph;
        }
    }

    public function save()
    {
        try {

            $this->schedule_time = (not_empty_bool(trim($this->schedule_time)) ? $this->schedule_time : date("Y-m-d H:i:s"));
            $this->message_token = $this->token();
            $this->sanitizeMessage();

            if ($this->submit_type === "whatsapp") $id = $this->save_whatsapp();
            elseif ($this->submit_type === "email") $id = $this->save_email();
            else $id = $this->save_general();

            return $id;

        } catch (Exception $exception) {
            echo($exception);
        }
    }

    public function save_submit()
    {
        try {
            $save = $this->save();
            $this->submitById($save);
        } catch (Exception $exception) {
            echo($exception);
        }
    }

    private function submitById($id)
    {
        if ($this->submit_type === "email") return $this->submit_email($id);
        if ($this->submit_type === "whatsapp") return $this->submit_whatsapp($id);
    }

    private function save_email(): bool
    {
        try {
            $database = new Database();
            $session = new AccountsSession();
            $id_account = $session->getAccountId();
            $database->query("INSERT INTO notifications (id_account, message_token, subject, preheader, message, submit_type, schedule_time) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $database->bind(1, $id_account);
            $database->bind(2, $this->message_token);
            $database->bind(3, $this->subject);
            $database->bind(4, $this->preheader);
            $database->bind(5, $this->message);
            $database->bind(6, $this->submit_type);
            $database->bind(7, $this->schedule_time);
            $database->execute();
            $last_id = $database->lastInsertId();
            return not_empty_bool($last_id) ? $last_id : 0;
        } catch (Exception $exception) {
            error_log($exception);
        }
        return false;
    }

    private function save_general(): int
    {
        try {
            $database = new Database();
            $session = new AccountsSession();
            $id_account = $session->getAccountId();
            $database->query("INSERT INTO notifications (id_account, message_token, message, submit_type, schedule_time) VALUES (?, ?, ?, ?, ?)");
            $database->bind(1, $id_account);
            $database->bind(2, $this->message_token);
            $database->bind(5, $this->message);
            $database->bind(6, $this->submit_type);
            $database->bind(7, $this->schedule_time);
            $database->execute();
            $last_id = $database->lastInsertId();
            return not_empty_bool($last_id) ? $last_id : 0;
        } catch (Exception $exception) {
            error_log($exception);
        }
        return false;
    }


    private function save_whatsapp(): int
    {
        try {
            $database = new Database();
            $session = new AccountsSession();
            $id_account = $session->getAccountId();
            $id_instance = $this->getInstanceId();
            $database->query("INSERT INTO notifications (id_account, id_instance, message_token, message, image, image_type, audio, document, message_link, submit_type,is_buttons, schedule_time, subject) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $database->bind(1, $id_account);
            $database->bind(2, $id_instance);
            $database->bind(3, $this->message_token);
            $database->bind(4, $this->message);
            $database->bind(5, $this->image);
            $database->bind(6, !not_empty_bool($this->image_type) ? "image" : $this->image_type);
            $database->bind(7, $this->audio);
            $database->bind(8, $this->document);
            $database->bind(9, $this->message_link);
            $database->bind(10, $this->submit_type);
            $database->bind(11, !not_empty_bool($this->is_buttons) ? "N" : $this->is_buttons);
            $database->bind(12, $this->schedule_time);
            $database->bind(13, $this->subject);
            $database->execute();
            $last_id = $database->lastInsertId();
            if (count($this->_buttons) > 0 && not_empty_bool($last_id)) {
                for ($i = 0; $i < count($this->_buttons); $i++) {
                    $database->query("INSERT INTO notifications_buttons (id_notification,button_text,button_url,button_action) VALUES (?,?,?,?)");
                    $database->bind(1, $last_id);
                    $database->bind(2, translate($this->_buttons[$i]['text']));
                    $database->bind(3, $this->_buttons[$i]['url']);
                    $database->bind(4, $this->_buttons[$i]['action']);
                    $database->execute();
                }
            }
            return not_empty_bool($last_id) ? $last_id : 0;
        } catch (Exception $exception) {
            error_log($exception);
        }
        return 0;
    }


    public function submit_email($id_notification = 0): bool
    {
        $env = new Env();
        $text = new Text();
        $mail = new PHPMailer(true);
        $result = false;

        try {

            $filter = ($id_notification > 0) ? " AND nt.id_notification = '" . $id_notification . "'" : "";

            $mail->isSMTP();                                            //Send using SMTP
            $mail->SMTPAuth = true;                                   //Enable SMTP authentication
            $mail->Host = $env->get("MAIL_SERVER");                     //Set the SMTP server to send through
            $mail->Username = $env->get("MAIL_USER");                          //SMTP username
            $mail->Password = $env->get("MAIL_PASSWORD");                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port = $env->get("MAIL_PORT");                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $mail->CharSet = 'UTF-8';

            $database = new Database();
            $database->query("SELECT nt.id_notification, nt.message, nt.subject, ac.email_address, ac.first_name, ac.last_name FROM notifications nt LEFT JOIN notifications_instances ni ON ni.id_notifications_instance = nt.id_instance LEFT JOIN accounts ac ON ac.id_account = nt.id_account WHERE (nt.is_active = 'Y' AND nt.is_submit = 'N' AND nt.submit_time IS NULL) AND (nt.schedule_time <= NOW() OR nt.schedule_time IS NULL) AND nt.submit_type = 'email' " . $filter . " LIMIT 0,10");
            $resultset = $database->resultSet();
            for ($i = 0; $i < count($resultset); $i++) {

                $id_notification = $resultset[$i]['id_notification'];

                $mail->setFrom('james@trackwithjames.com', translate('James from TrackWithJames'));
                $mail->addReplyTo('james@trackwithjames.com', translate('James from TrackWithJames'));

                $mail->addAddress($resultset[$i]['email_address'], $resultset[$i]['first_name'] . " " . $resultset[$i]['last_name']);

                $mail->isHTML(true);
                $mail->Subject = $resultset[$i]['subject'];
                $mail->Body = $resultset[$i]['message'];
                $mail->AltBody = strip_tags($resultset[$i]['message']);
                $sent = $mail->send();

                if ($sent) {
                    $database->query("UPDATE notifications SET is_submit = 'Y', submit_time = CURRENT_TIMESTAMP WHERE id_notification = ?");
                    $database->bind(1, $id_notification);
                    $database->execute();
                    $result = true;
                }
            }

        } catch (Exception $exception) {
            error_log($exception);
        }
        return $result;
    }

    public function submit_whatsapp($id_notification = 0): bool
    {
        $result = false;
        try {
            $data = [];
            $buttons = [];
            $filter = ($id_notification > 0) ? " AND nt.id_notification = '" . $id_notification . "'" : "";
            $database = new Database();
            $database->query("SELECT nt.id_notification, nt.message_token, nt.message, nt.subject, nt.image, nt.image_type, nt.audio, nt.document, nt.message_link, nt.is_buttons, ni.instance_api, ac.first_name, ac.last_name, ac.mobile_phone FROM notifications nt LEFT JOIN notifications_instances ni ON ni.id_notifications_instance = nt.id_instance LEFT JOIN accounts ac ON ac.id_account = nt.id_account WHERE (nt.is_active = 'Y' AND nt.is_submit = 'N' AND nt.submit_time IS NULL) AND (nt.schedule_time <= NOW() OR nt.schedule_time IS NULL) AND nt.submit_type = 'whatsapp' " . $filter . " LIMIT 0,10");
            $resultset = $database->resultSet();
            for ($i = 0; $i < count($resultset); $i++) {

                $id_notification = $resultset[$i]['id_notification'];
                $image = $resultset[$i]['image'];
                $image_type = $resultset[$i]['image_type'];
                $is_buttons = $resultset[$i]['is_buttons'];
                $message_link = $resultset[$i]['message_link'];

                $instance_api = $resultset[$i]['instance_api'];

                $data['phone'] = $resultset[$i]['mobile_phone'];
                $data['message'] = strip_tags($resultset[$i]['message']);
                $data['footer'] = translate("James - Smart Business");

                if (not_empty_bool($image) && $image_type === "image") {
                    $data['image'] = $resultset[$i]['image'];
                    if (not_empty_bool($resultset[$i]['message'])) $data['caption'] = $resultset[$i]['message'];
                } elseif (not_empty_bool($image) && $image_type === "sticker") {
                    $data['sticker'] = $resultset[$i]['image'];
                }

                if (not_empty_bool($message_link)) {
                    $data['linkUrl'] = $resultset[$i]['message_link'];
                    $data['title'] = $resultset[$i]['subject'];
                    $data['linkDescription'] = $resultset[$i]['message'];
                }

                if (not_empty($is_buttons) === "Y") {
                    $database->query("SELECT * FROM notifications_buttons WHERE id_notification = ?");
                    $database->bind(1, $id_notification);
                    $resultset2 = $database->resultSet();
                    for ($x = 0; $x < count($resultset2); $x++) {
                        if ($resultset2[$x]['button_action'] === "CALL") {
                            $buttons[] = [
                                "id" => $x,
                                "type" => $resultset2[$x]['button_action'],
                                "phone" => $resultset2[$x]['button_url'],
                                "label" => $resultset2[$x]['button_text'],
                            ];
                        } else if ($resultset2[$x]['button_action'] === "URL") {
                            $buttons[] = [
                                "id" => $x,
                                "type" => $resultset2[$x]['button_action'],
                                "url" => $resultset2[$x]['button_url'],
                                "label" => $resultset2[$x]['button_text'],
                            ];
                        } else {
                            $buttons[] = [
                                "id" => $x,
                                "type" => $resultset2[$x]['button_action'],
                                "label" => $resultset2[$x]['button_text'],
                            ];
                        }
                    }
                    if (count($buttons) > 0) $data['buttonActions'] = $buttons;
                }

                $client = new Client();
                $response = $client->post($instance_api, [
                    GuzzleHttp\RequestOptions::JSON => $data,
                    'verify' => false
                ]);

                $response = json_decode((string)$response->getBody(), true);

                if (array_key_exists("zaapId", $response) && array_key_exists("messageId", $response)) {
                    $database->query("UPDATE notifications SET id_zaap = ?, id_message = ?, is_submit = 'Y', submit_time = CURRENT_TIMESTAMP WHERE id_notification = ?");
                    $database->bind(1, $response['zaapId']);
                    $database->bind(2, $response['messageId']);
                    $database->bind(3, $id_notification);
                    $database->execute();
                    $result = true;
                }


            }
        } catch (Exception $exception) {
            error_log($exception);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            error_log($e);
        }
        return $result;
    }


}