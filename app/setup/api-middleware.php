<?php
// USR = root_nex
// MPSS = [PbC$;P{*t/r'nL#_27FJaWg*9W=ZQ

$authorizations = [
    "novaexperiencia" => [
        "root_authorization" => "bm92YWV4cGVyaWVuY2lhOltQYkMkO1B7KnQvciduTCNfMjdGSmFXZyo5Vz1aUQ",
        "basic_authorization" => "YnwNpYH6AjFr7CY8RF5cbrWqzPPPSeCsbN7dbQSY9UhYeTyh8annmzZZAZLuJSa3snr4SsdEfeUCnGrPPe6bR9xMr6AXPTtnYVVAaRA2qb2v4bkuqdD4jqXLDf3Dhx93UP6HARD6xV7hyPpnZ5u9te3QvFZr3jUAnfBJ9YkFvsJTKUgBRdBtbQCrqqZQNKJyGTTVX976nycQUqRzPnST4FzCZnqP5FRpw7FSyTBnNBtzAMWd7qq5MjtGdbHr7NcK"
    ],
    "maestriaonline" => [
        "root_authorization" => "bWFlc3RyaWFvbmxpbmU6W1BiQyQ7UHsqdC9yJ25MI18yN0ZKYVdnKjlXPVpR",
        "basic_authorization" => "6CLuvquYYBQbAQmSWBvxhdjpguLdWzjESKvt8VX9uQ5rfCPEAN7QwrW9E8dBhwV2yNdXsNwjyXaWPCRPgLYHPF6ypujhHumYQP9AvPRPrrPEPRApC8daStFXEX78UpAcUZaZxdZs5LvNuU4nqDyAGv9pPmBPQ9hVVWffyxtXuZCHvvjWexTz2hjg8czvQ8jzaBYBt4pkzPE2tTrGnmEavVpDbFAgspsXEnEvPNfFVtS9CsGeELFPAgx3M7g88e7D"
    ]
];

$call = get_request("call");
$token = null;
$basic_token = null;
$headers = apache_request_headers();
$authorize = 0;


$_BASIC_AUTHORIZATIONS = [
    "transactions/executor" => false,
    "notifications/due-and-unnotified-last-day" => true,
    "notifications/future-days-transactions" => true,
    "notifications/sms" => true,
    "notifications/email" => true,
    "notifications/whatsapp" => true,
    "payments/sync" => true
];


if (isset($headers['Authorization'])) {
   $token = $headers['Authorization'];
}

if (isset($_REQUEST['authorization'])) {
    $basic_token = $_REQUEST['authorization'];
}

if (isset($_REQUEST['application'])) {
    $application = $_REQUEST['application'];
    if (array_key_exists($application, $authorizations)) {

        if($token !== null && $token === $authorizations[$application]['root_authorization']) $authorize = 1;
        if($basic_token !== null && $basic_token === $authorizations[$application]['basic_authorization']) $authorize = 2;

    }
}


if($authorize !== 1) {
    if ($authorize === 2) {
        if (!array_key_exists($call, $_BASIC_AUTHORIZATIONS)) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }else{
            if ($_BASIC_AUTHORIZATIONS[$call] !== true) {
                header("HTTP/1.1 401 Unauthorized");
                exit;
            }
        }
    } else {
        header("HTTP/1.1 401 Unauthorized");
        exit;
    }
}