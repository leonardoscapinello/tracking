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
                <?= $static->img("escolaspace-dark.png")->ratio(32)->save()->html() ?>
            </div>
            <h1><?= translate("Log in to your SpacePlay account") ?></h1>

            <div class="form-widget">

                <?= $auth_status === "SUCCESS" ? $alerts->success()->text("Welcome to your area!")->display() : "" ?>
                <?= $auth_status === "WRONG_DATA" ? $alerts->danger()->text("Wrong username or password")->display() : "" ?>
                <?= $auth_status === "EMPTY_DATA" ? $alerts->warning()->text("Please fill all fields")->display() : "" ?>

                <?= $fields->hidden()->name("commit")->value("Y")->output() ?>
                <?= $fields->text()->label("Username or email address")->id("username")->name("username")->placeholder("username/email")->hash()->required()->output() ?>
                <?= $fields->text("password")->label("Password")->id("password")->name("password")->placeholder("your password")->hash()->required()->output() ?>


                <?= $fields->button()->value("Log In")->id("commit")->name("commit")->classList("btn-primary")->hash()->output() ?>

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