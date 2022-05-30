<?php
if (!$session->isLogged()) {
    header("location: " . $url->application("authenticate")->page("login")->actualAsNext()->output());
    die;
}
