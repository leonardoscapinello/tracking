<?php
require_once("app/setup/loaders.php");


if ($modules->isModulesExists()) {
    if ($modules->isPrivate()) {
        require_once("app/setup/session.validator.php");
    }
} else {
    echo $modules->getCategorySlug();
    //require_once("app/setup/session.validator.php");
}

if (!not_empty(get_request("module_category")) && !not_empty(get_request("module_slug")) && !not_empty(get_request("domain"))) {
    $url->application("dashboard")->page("home")->add(["hgl" => "pt-BR","f" => "domain"])->redirect();
    die;
}


ob_start("sanitize_output");
?>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Space Play - Escola Space</title>
    <?= $static->css([
        "proxima-nova.css",
        "fontawesome.min.css",
        "reset.css",
        "container.css",
        "noty.css",
        "stylesheet.css",
        "tables.css",
    ])->replace("static/fonts/", $env->get("APP_URL") . "/static/fonts/")->minify()->output("stylesheet.min.css")->embed();
    ?>
    <?php if (!true) { ?>
        <style>
            :root {
                --header-background: #1D2F38;
                --header-color: #FFFFFF;
                --header-icon-color: #9dacb0;
                --header-search-backgroud: #132028;
                --header-search-border: #132028;
                --header-search-placeholder: #677480;
                --component-border: #F3F3F4;
                --brand: #0B66FF;
                --brand-light: #d0ecff;
                --brand-dark: #445ccc;
                --brand-dark-1: #4056c0;
                --brand-dark-2: #304190;
                --brand-light-1: #9db9ff;
                --brand-separator: #ffffff;
                --font-size-main: 15px;
                --font-family: "Proxima Nova", sans-serif;
                --primary-text-color: #1B1D1F;
                --primary-input-background: #F7F8F9;
                --primary-input-border: #F1F2F2;
                --object-dark: none;
                --object-light: inherit;
            }
        </style>
    <?php } ?>
    <script type="text/javascript">
        let config = {
            api: {
                url: "<?=$env->get("APP_API")?>"
            }
        }
    </script>
    <?= $static->js([
        "axios.min.js", "editor.js", "editor/SimpleImage.js", "post.js", "feed.js"
    ])->embed();

    ?>
</head>
<body class="light">

<?php if ($modules->isDashboard()) { ?>
    <div class="wrapper">
        <header>
            <div class="header">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-2 col-lg-3 col-sm-6 main-center-flex">
                            <div class="main-branding">
                                <div class="branding">
                                    <?= $static->img("trackwithjames.svg")->html() ?>
                                </div>
                            </div>
                        </div>
                        <div class="offset-1"></div>
                        <div class="col-xl-6 col-lg-6 col-sm-5 main-center-flex">
                            <div class="search-form">
                                <div class="input-icon">
                                    <i class="fal fa-search" aria-label="-1"></i>
                                </div>
                                <form accept-charset="utf-8" method="GET"
                                      action="<?= $url->application("classroom")->page("search")->output() ?>">
                                    <input type="search" name="q" value="<?= get_request("q") ?>"
                                           placeholder="<?= translate("Type to search") ?>"/>
                                </form>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-sm-6 right">

                            <div class="profile-widgets">
                                <div class="actions">
                                    <ul>
                                        <li><a href="#"><i class="fal fa-bell"></i></a></li>
                                        <li><a href="#"><i class="fal fa-cog"></i></a></li>
                                    </ul>
                                </div>
                                <div class="profile-image">
                                    <img src="<?= $account->getProfileImage() ?>">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section>
            <div class="content classroom-feed">

                <div class="container">
                    <div class="row">


                        <?php if (!$modules->isFullWidth()) { ?>
                            <div class="col-xl-3 col-lg-3 col-sm-12">
                                <div class="sidebar-sticky">
                                    <?= $modules->navigation() ?>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="<?= ($modules->isFullWidth() ? "col-xl-12 col-lg-12 col-sm-12" : "col-xl-9 col-lg-9 col-sm-12") ?>">

                            <?php
                            $route_file = $modules->get();
                            if (not_empty($route_file)) require $route_file;
                            else  echo "Page not found";
                            ?>

                        </div>
                    </div>
                </div>

            </div>
        </section>

    </div>

<?php } else {

    $route_file = $modules->get();
    if (not_empty($route_file)) require $route_file;
    else  echo "Page not found";

} ?>


</body>
</html>
