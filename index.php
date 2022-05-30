<?php
require_once("app/setup/loaders.php");

if (!$routes->getIsPublic()) {
    require_once("app/setup/session.validator.php");
}

if (!not_empty(get_request("classroom_category")) && !not_empty(get_request("classroom_slug")) && !not_empty(get_request("route_slug"))) {
    $url->application("classroom")->page("feed")->add(["hgl" => "pt-BR"])->redirect();
    die;
}


$posts = new Posts();
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
    <script src="https://cdn.gravitec.net/storage/850d87b17d2e74f3c89d06b841a9dc54/client.js" async></script>
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
<body class="<?= $routes->getCategorySlug() ?> light">

<?php if ($routes->getIsClassroom()) { ?>
    <div class="wrapper">
        <header>
            <div class="header">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-2 col-lg-3 col-sm-6 main-center-flex">
                            <div class="main-branding">
                                <div class="branding">
                                    <?= $static->img("escolaspace-dark.png")->ratio(32)->save()->classList("object-dark")->html() ?>
                                    <?= $static->img("escolaspace-light.png")->ratio(32)->save()->classList("object-light")->html() ?>
                                </div>
                                <div class="discover">
                                    <?= translate("Discover") ?>
                                    <sup><?= translate("Beta") ?></sup>
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
                                        <?php if ($classroom->getIsWritable()) { ?>
                                            <li><a href="#"><i class="fal fa-plus-square"></i></a></li>
                                        <?php } ?>
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
                        <div class="col-xl-3 col-lg-3 col-sm-12">
                            <div class="sidebar-sticky">
                                <?= $classroom->navigation() ?>
                            </div>
                        </div>
                        <div class="col-xl-6 col-lg-6 col-sm-12">


                            <?php
                            $route_file = $routes->get();
                            if (not_empty($route_file)) require $route_file;
                            else  echo "Page not found";

                            ?>

                        </div>
                        <div class="col-xl-3 col-lg-3 col-sm-12">

                        </div>
                    </div>
                </div>

            </div>
        </section>

    </div>


    <script type="text/javascript">
        if (document.getElementById("editor") !== undefined) {
            let post = new Post();
            const editor = new EditorJS({
                holder: "editor",
                autofocus: true,

                tools: {
                    image: {
                        class: ImageTool,
                        config: {
                            endpoints: {
                                byFile: config.api.url + '/images/upload',
                            },
                            additionalRequestData: {
                                id: "<?=$url->getId()?>"
                            },
                            buttonContent: `<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3.15 13.628A7.749 7.749 0 0 0 10 17.75a7.74 7.74 0 0 0 6.305-3.242l-2.387-2.127-2.765 2.244-4.389-4.496-3.614 3.5zm-.787-2.303l4.446-4.371 4.52 4.63 2.534-2.057 3.533 2.797c.23-.734.354-1.514.354-2.324a7.75 7.75 0 1 0-15.387 1.325zM10 20C4.477 20 0 15.523 0 10S4.477 0 10 0s10 4.477 10 10-4.477 10-10 10z"></path></svg><?=translate("Select an Image")?>`
                        }
                    }
                },
            });
            document.getElementById("save-button").onclick = function () {
                editor.save().then((outputData) => {
                    post.save('<?=$url->getId()?>', outputData);
                }).catch((error) => {
                    console.log('Saving failed: ', error)
                });
            }
        }
    </script>
<?php } else {


    $route_file = $routes->get();
    if (not_empty($route_file)) require $route_file;
    else  echo "Page not found";

} ?>


</body>
</html>
