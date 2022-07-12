<?php
$username = get_request("username", false, false, true);
$password = get_request("password", false, false, true);
$commit = get_request("commit");
$auth_status = "";
if (!$session->isLogged()) {
    if (not_empty_bool($username) && not_empty_bool($password) && not_empty($commit) === "Y") {
        $authenticate = $session->username($username)->password($password)->authenticate();
        $auth_status = $authenticate > 0 ? "SUCCESS" : "WRONG_DATA";
        if ($auth_status === "SUCCESS") {
            $url->application("classroom")->page("classroom")->add(["hgl" => "login"])->location(true);
            die;
        }
    } else {
        if (not_empty($commit) === "Y") {
            $auth_status = "EMPTY_DATA";
        }
    }
} else {
    $url->application("classroom")->page("classroom")->add(["hgl" => "login"])->location(true);
    die;
}
?>
<div class="center-layout">
    <form method="POST">
        <div class="authenticate-widget">

            <div class="branding">
                <svg viewBox="0 0 130.89 127.38" xmlns="http://www.w3.org/2000/svg" width="32" fill="currentColor">
                    <path d="m116.64 58.48c19.86-6.53 17.49 38.67 1.93 34.44l-1.93-34.44z"/>
                    <path d="m12.73 92.33c-20.41 4.53-13.56-40.21 1.5-34.47l-1.5 34.47z"/>
                    <path d="m50.3 96.9s-19.41 1.78-19.41-10.37 13.33-8.59 13.33-8.59 6.07 0 6.07 18.96z"/>
                    <path d="m79.12 96.9s19.41 1.78 19.41-10.37-13.33-8.59-13.33-8.59-6.07 0-6.07 18.96z"/>
                    <path d="M65.57,28.57c-73.51,1.19-73.49,97.63,0,98.81,73.5-1.19,73.5-97.63,0-98.81Zm42.39,59.79C65.92,142.02-.69,81.77,21.94,67.22c3.59-2.3,8.12-2.51,12.07-.9,10.39,4.24,39.5,13.53,66.56-1.77,4.75-1.94,17.07,4.69,8.57,21.95-.32,.66-.72,1.28-1.18,1.86Z"/>
                    <path d="m77.68 29.56s-1.33-10.56-8.44-10.56l-3.67-19-3.67 19c-7.11 0-8.44 10.56-8.44 10.56"/>
                </svg>


            </div>
            <h1><?= translate("Log in to your account") ?></h1>

            <div class="form-widget">

                <?= $auth_status === "SUCCESS" ? $alerts->success()->text("Welcome to your area!")->display() : "" ?>
                <?= $auth_status === "WRONG_DATA" ? $alerts->danger()->text("Wrong username or password")->display() : "" ?>
                <?= $auth_status === "EMPTY_DATA" ? $alerts->warning()->text("Please fill all fields")->display() : "" ?>

                <?= $fields->hidden()->name("commit")->value("Y")->output() ?>
                <?= $fields->text()->label("Username or email address")->id("username")->name("username")->placeholder("username/email")->hash()->required()->output() ?>
                <?= $fields->text("password")->label("Password")->id("password")->name("password")->placeholder("your password")->hash()->required()->output() ?>

                <div align="center">
                    <?= $fields->button()->value("Log In")->id("commit")->name("commit")->classList("btn btn-primary")->hash()->output() ?>
                </div>

                <div class="action-links">
                    <ul>
                        <li>
                            <a href="<?= $url->application("authenticate")->page("forget-password")->output() ?>"><?= translate("Forgot your password?") ?></a>
                        </li>
                    </ul>
                </div>

                <div class="copyright-terms"><?= $env->get("COPYRIGHT_TERMS") ?></div>

            </div>
        </div>
    </form>
</div>