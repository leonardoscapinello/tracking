<?php

class Notification
{

    public function register()
    {
        try {
            $message_text = get_request("message_text");
            $message_id = get_request("id");
            $notification = file_get_contents('php://input');
            parse_str($notification, $notification);
            $date = new Date();
            $contact = array();
            if (array_key_exists("contact", $notification)) {
                $contact = $notification['contact'];
            }

            $notification_token = $this->token();
            $full_name = $this->get("first_name", $contact);
            $email_address = $this->get("email", $contact);
            $phone_number = $this->get("phone", $contact);

            $schedule_time = get_request("schedule_time");
            $plus_time = get_request("plus_time");

            if ($schedule_time === null) $schedule_time = date("Y-m-d H:i:s");

            $date->set($schedule_time);
            $date->format("Y-m-d H:i:s");
            if ($plus_time !== null) $date->add($plus_time);

            $phone_number = $this->phone($phone_number);
            $schedule_time = $date->output();

            $database = new Database();

            if ($message_id !== null && $message_id !== "") {
                $database->query("SELECT message_text FROM notifications_templates WHERE id_template = ?");
                $database->bind(1, $message_id);
                $result = $database->resultSet();
                if (count($result) > 0) $message_text = $result[0]['message_text'];
            }

            if ($message_text === null || $phone_number === null) return false;

            $message_text = str_replace("{{email_address}}", $email_address, $message_text);
            $message_text = str_replace("{{full_name}}", $full_name, $message_text);
            $message_text = str_replace("{{phone_number}}", $phone_number, $message_text);
            $message_text = str_replace("{{break}}", PHP_EOL, $message_text);


            $database->query("INSERT INTO notifications (notification_token, full_name, email_address, phone_number, message_text, schedule_time) VALUES (?, ?, ?, ?, ?, ?)");
            $database->bind(1, $notification_token);
            $database->bind(2, $full_name);
            $database->bind(3, $email_address);
            $database->bind(4, $phone_number);
            $database->bind(5, $message_text);
            $database->bind(6, $schedule_time);
            $database->execute();

        } catch (Exception $exception) {
            logger($exception);
        }
    }

    public function bulk()
    {
        try {
            $text = new Text();
            $database = new Database();
            $database->query("SELECT id_notification, full_name, email_address, phone_number, message_text FROM notifications WHERE (NOW() >= schedule_time OR schedule_time IS NULL OR schedule_time = '') AND (submit_time IS NULL OR submit_time = '')");
            $results = $database->resultSet();
            for ($i = 0; $i < count($results); $i++) {
                $message = $results[$i]['message_text'];
                $email_address = $results[$i]["email_address"];
                $full_name = $results[$i]["full_name"];
                $phone_number = $this->phone($results[$i]["phone_number"]);
                $message = str_replace("{{email_address}}", $email_address, $message);
                $message = str_replace("{{full_name}}", $full_name, $message);
                $message = str_replace("{{phone_number}}", $phone_number, $message);
                $message = str_replace("{{break}}", PHP_EOL, $message);
                if ($phone_number !== null && $message !== null) {
                    $sent = $this->submit($phone_number, $message);
                    if ($sent) {
                        $this->markAsSent($results[$i]["id_notification"]);
                    }
                }
            }
        } catch (Exception $exception) {
            logger($exception);
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            logger($e);
        }
    }

    public function markAsSent($id_notification)
    {
        try {
            $database = new Database();
            $database->query("UPDATE notifications SET submit_time = CURRENT_TIMESTAMP WHERE id_notification = ?");
            $database->bind(1, $id_notification);
            $database->execute();
        } catch (Exception $exception) {
            logger($exception);
        }
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function submit($number, $message)
    {
        $env = new Env();
        $client = new GuzzleHttp\Client();
        $response = $client->request(
            "GET",
            "https://v1.utalk.chat/send/8z9qmx6/",
            [
                'query' => [
                    "cmd" => "chat",
                    "to" => $number . "@c.us",
                    "msg" => $message
                ]
            ]
        );
        $statusCode = $response->getStatusCode();
        if ($statusCode >= 200 && $statusCode < 300) return true;
        return false;
    }

    private function get(string $index, array $array)
    {
        if (array_key_exists($index, $array)) {
            $element = $array[$index];
            if ($element !== null && $element !== "") return $array[$index];
        }
        return null;
    }

    public function phone(string $phone)
    {
        try {
            $phone = preg_replace('/[^a-z0-9]/i', '', $phone);
            $length = strlen($phone);
            if ($length === 11 || $length === 10) return "55" . $phone;
            return $phone;
        } catch (Exception $exception) {
            logger($exception);
        }
    }


    private function token(): string
    {
        return md5(uniqid(date("Y-m-d H:i:s"), true)) . "-" . md5(uniqid(date("d/m/Y H:i:s"), true));
    }


}