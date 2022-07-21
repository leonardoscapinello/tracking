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

if (!not_empty(get_request("module_slug")) || ($modules->isDomainRequired() && !not_empty_bool($domains->getDomainByCookie()))) {
    $url->application("dashboard")->page("select-domain")->add(["hgl" => "pt-BR", "f" => "domain"])->redirect();
    die;
}
ob_start("sanitize_output");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Dashboard | TrackWithJames</title>
    <meta name="description" content="Responsive, Bootstrap, BS4"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <?= $static->css([
        "bootstrap.css",
        "theme.css",
        "nice-select.css",
        "style.css",
        "noty.css",
        "fontawesome.min.css",
        "sf-pro-text.css",
    ])->replace("static/fonts/", $env->get("APP_URL") . "/static/fonts/")->minify()->output("trackwithjames.css")->embed();
    ?>
</head>


<?php if ($modules->isDashboard()) { ?>

    <body class="layout-row">


    <!-- ############ Aside START-->
    <div id="aside" class="page-sidenav no-shrink bg-light nav-dropdown fade" aria-hidden="true">
        <div class="sidenav h-100 modal-dialog bg-light">
            <!-- sidenav top -->
            <div class="navbar">
                <!-- brand -->
                <a href="<?= $url->application("dashboard")->page("dashboard")->output() ?>" class=" navbar-brand ">

                    <svg viewBox="0 0 130.89 127.38" xmlns="http://www.w3.org/2000/svg" width="32" fill="currentColor">
                        <path d="m116.64 58.48c19.86-6.53 17.49 38.67 1.93 34.44l-1.93-34.44z"/>
                        <path d="m12.73 92.33c-20.41 4.53-13.56-40.21 1.5-34.47l-1.5 34.47z"/>
                        <path d="m50.3 96.9s-19.41 1.78-19.41-10.37 13.33-8.59 13.33-8.59 6.07 0 6.07 18.96z"/>
                        <path d="m79.12 96.9s19.41 1.78 19.41-10.37-13.33-8.59-13.33-8.59-6.07 0-6.07 18.96z"/>
                        <path d="M65.57,28.57c-73.51,1.19-73.49,97.63,0,98.81,73.5-1.19,73.5-97.63,0-98.81Zm42.39,59.79C65.92,142.02-.69,81.77,21.94,67.22c3.59-2.3,8.12-2.51,12.07-.9,10.39,4.24,39.5,13.53,66.56-1.77,4.75-1.94,17.07,4.69,8.57,21.95-.32,.66-.72,1.28-1.18,1.86Z"/>
                        <path d="m77.68 29.56s-1.33-10.56-8.44-10.56l-3.67-19-3.67 19c-7.11 0-8.44 10.56-8.44 10.56"/>
                    </svg>

                    <!-- <img src="<?= $env->get("APP_URL") ?>/static/img/logo.png" alt="..."> -->
                    <span class="hidden-folded d-inline l-s-n-1x ">TrackWithJames</span>
                </a>
                <!-- / brand -->
            </div>
            <!-- Flex nav content -->
            <div class="flex scrollable hover">
                <div class="nav-active-text-primary" data-nav>

                    <ul class="nav bg">
                        <li>
                            <div class="p-3 nav-fold">


                                <?php
                                $list = $domains->getAllDomains();
                                if (count($list) > 0) { ?>
                                    <a href="#" class="btn btn-white btn-block text-align-auto" data-toggle="dropdown"
                                       data-pjax-state="">
                                        <?php if (not_empty_bool($domains->getDomain())) { ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                 viewBox="0 0 24 24"
                                                 style="margin-right: 2px;margin-left: -7px" fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-link">
                                                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path>
                                                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path>
                                            </svg>
                                            <span class="mx-1 hidden-folded"><?= $text->set($domains->getDomain())->short(16)->output() ?></span>
                                        <?php } else { ?>
                                            <span class="mx-1 hidden-folded"><?= translate("Select a domain") ?></span>
                                        <?php } ?>
                                    </a>
                                    <div class="dropdown-menu text-sm">


                                        <?php for ($i = 0; $i < count($list); $i++) { ?>
                                            <a class="dropdown-item"
                                               href="<?= $url->actualPage()->add(["set" => $list[$i]['public_key']])->actualAsNext()->output() ?>"
                                               data-pjax-state="">
                                                <?= $text->set($list[$i]['domain'])->short(30)->output() ?>
                                            </a>
                                        <?php } ?>


                                        <a class="dropdown-item"
                                           style="border: 1px rgba(0,0,0,.05) solid;margin-top:20px;line-height:30px;border-left: none;border-right: none"
                                           href="<?= $url->application("dashboard")->page("new-domain")->output() ?>"
                                           data-pjax-state="">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                                 viewBox="0 0 24 24" style="position: relative;top: -1px"
                                                 fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round"
                                                 stroke-linejoin="round" class="feather feather-plus">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                            <?= translate("Register new domain") ?>
                                        </a>

                                    </div>
                                <?php } else { ?>
                                    <a href="<?= $url->application("dashboard")->page("new-domain")->output() ?>"
                                       class="btn btn-white btn-block text-align-auto">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                             viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                             stroke-linejoin="round" class="feather feather-plus">
                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                        </svg>
                                        <span class="mx-1 hidden-folded"><?= translate("Register your domain") ?></span>
                                    </a>
                                <?php } ?>
                            </div>
                        </li>
                    </ul>

                    <?= $modules->navigation() ?>
                </div>
            </div>
            <!-- sidenav bottom -->
            <div class="no-shrink ">
                <div class="p-3 d-flex align-items-center">
                    <div class="text-sm hidden-folded text-muted">
                        Trial: 35%
                    </div>
                    <div class="progress mx-2 flex" style="height:4px;">
                        <div class="progress-bar gd-success" style="width: 35%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ############ Aside END-->
    <div id="main" class="layout-column flex">
        <!-- ############ Header START-->
        <div id="header" class="page-header ">
            <div class="navbar navbar-expand-lg">
                <!-- brand -->
                <a href="<?= $url->application("dashboard")->page("dashboard")->output() ?>"
                   class="navbar-brand d-lg-none">

                    <svg viewBox="0 0 130.89 127.38" xmlns="http://www.w3.org/2000/svg" width="32" fill="currentColor">
                        <path d="m116.64 58.48c19.86-6.53 17.49 38.67 1.93 34.44l-1.93-34.44z"/>
                        <path d="m12.73 92.33c-20.41 4.53-13.56-40.21 1.5-34.47l-1.5 34.47z"/>
                        <path d="m50.3 96.9s-19.41 1.78-19.41-10.37 13.33-8.59 13.33-8.59 6.07 0 6.07 18.96z"/>
                        <path d="m79.12 96.9s19.41 1.78 19.41-10.37-13.33-8.59-13.33-8.59-6.07 0-6.07 18.96z"/>
                        <path d="M65.57,28.57c-73.51,1.19-73.49,97.63,0,98.81,73.5-1.19,73.5-97.63,0-98.81Zm42.39,59.79C65.92,142.02-.69,81.77,21.94,67.22c3.59-2.3,8.12-2.51,12.07-.9,10.39,4.24,39.5,13.53,66.56-1.77,4.75-1.94,17.07,4.69,8.57,21.95-.32,.66-.72,1.28-1.18,1.86Z"/>
                        <path d="m77.68 29.56s-1.33-10.56-8.44-10.56l-3.67-19-3.67 19c-7.11 0-8.44 10.56-8.44 10.56"/>
                    </svg>


                    <!-- <img src="<?= $env->get("APP_URL") ?>/static/img/logo.png" alt="..."> -->
                    <span class="hidden-folded d-inline l-s-n-1x d-lg-none">TWJames</span>
                </a>
                <!-- / brand -->
                <!-- Navbar collapse -->
                <div class="collapse navbar-collapse order-2 order-lg-1" id="navbarToggler">
                    <form class="input-group m-2 my-lg-0 ">
                        <div class="input-group-prepend">
                            <button type="button" class="btn no-shadow no-bg px-0 text-inherit">
                                <i data-feather="search"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control no-border no-shadow no-bg typeahead"
                               placeholder="Search components..." data-plugin="typeahead"
                               data-api="<?= $env->get("APP_URL") ?>/static/api/menu.json">
                    </form>
                </div>
                <ul class="nav navbar-menu order-1 order-lg-2">
                    <li class="nav-item d-none d-sm-block">
                        <a class="nav-link px-2" data-toggle="fullscreen" data-plugin="fullscreen">
                            <i data-feather="maximize"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link px-2" data-toggle="dropdown">
                            <i data-feather="settings"></i>
                        </a>
                        <!-- ############ Setting START-->
                        <div class="dropdown-menu dropdown-menu-center mt-3 w-md animate fadeIn">
                            <div class="setting px-3">
                                <div class="mb-2 text-muted">
                                    <strong>Setting:</strong>
                                </div>
                                <div class="mb-3" id="settingLayout">
                                    <label class="ui-check ui-check-rounded my-1 d-block">
                                        <input type="checkbox" name="stickyHeader">
                                        <i></i>
                                        <small>Sticky header</small>
                                    </label>
                                    <label class="ui-check ui-check-rounded my-1 d-block">
                                        <input type="checkbox" name="stickyAside">
                                        <i></i>
                                        <small>Sticky aside</small>
                                    </label>
                                    <label class="ui-check ui-check-rounded my-1 d-block">
                                        <input type="checkbox" name="foldedAside">
                                        <i></i>
                                        <small>Folded Aside</small>
                                    </label>
                                    <label class="ui-check ui-check-rounded my-1 d-block">
                                        <input type="checkbox" name="hideAside">
                                        <i></i>
                                        <small>Hide Aside</small>
                                    </label>
                                </div>
                                <div class="mb-2 text-muted">
                                    <strong>Color:</strong>
                                </div>
                                <div class="mb-2">
                                    <label class="radio radio-inline ui-check ui-check-md">
                                        <input type="radio" name="bg" value="">
                                        <i></i>
                                    </label>
                                    <label class="radio radio-inline ui-check ui-check-color ui-check-md">
                                        <input type="radio" name="bg" value="bg-dark">
                                        <i class="bg-dark"></i>
                                    </label>
                                </div>
                                <div class="mb-2 text-muted">
                                    <strong>Layouts:</strong>
                                </div>
                                <div class="mb-3">
                                    <a href="dashboard.html" class="btn btn-xs btn-white no-ajax mb-1">Default</a>
                                    <a href="layout.a.html?bg" class="btn btn-xs btn-primary no-ajax mb-1">A</a>
                                    <a href="layout.b.html?bg" class="btn btn-xs btn-info no-ajax mb-1">B</a>
                                    <a href="layout.c.html?bg" class="btn btn-xs btn-success no-ajax mb-1">C</a>
                                    <a href="layout.d.html?bg" class="btn btn-xs btn-warning no-ajax mb-1">D</a>
                                </div>
                            </div>
                        </div>
                        <!-- ############ Setting END-->
                    </li>
                    <!-- Notification -->
                    <li class="nav-item dropdown">
                        <a class="nav-link px-2 mr-lg-2" data-toggle="dropdown">
                            <i data-feather="bell"></i>
                            <span class="badge badge-pill badge-up bg-primary">4</span>
                        </a>
                        <!-- dropdown -->
                        <div class="dropdown-menu dropdown-menu-right mt-3 w-md animate fadeIn p-0">
                            <div class="scrollable hover" style="max-height: 250px">
                                <div class="list list-row">
                                    <div class="list-item " data-id="6">
                                        <div>
                                            <a href="#">
                                                    <span class="w-32 avatar gd-danger">
		                          <img src="<?= $env->get("APP_URL") ?>/static/img/a6.jpg" alt=".">
		                    </span>
                                            </a>
                                        </div>
                                        <div class="flex">
                                            <div class="item-feed h-2x">
                                                Just saw this on the
                                                <a href='#'>@eBay</a> dashboard, dude is an absolute unit.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-item " data-id="12">
                                        <div>
                                            <a href="#">
                                                    <span class="w-32 avatar gd-info">
		                          A
		                    </span>
                                            </a>
                                        </div>
                                        <div class="flex">
                                            <div class="item-feed h-2x">
                                                <a href='#'>Support</a> team updated the status
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-item " data-id="8">
                                        <div>
                                            <a href="#">
                                                    <span class="w-32 avatar gd-success">
		                          <img src="<?= $env->get("APP_URL") ?>/static/img/a8.jpg" alt=".">
		                    </span>
                                            </a>
                                        </div>
                                        <div class="flex">
                                            <div class="item-feed h-2x">
                                                The biggest software developer conference
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-item " data-id="4">
                                        <div>
                                            <a href="#">
                                                    <span class="w-32 avatar gd-success">
		                          <img src="<?= $env->get("APP_URL") ?>/static/img/a4.jpg" alt=".">
		                    </span>
                                            </a>
                                        </div>
                                        <div class="flex">
                                            <div class="item-feed h-2x">
                                                Big News! Introducing
                                                <a href='#'>NextUX</a> Enterprise 2.1 - additional #Windows Server
                                                support
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-item " data-id="11">
                                        <div>
                                            <a href="#">
                                                    <span class="w-32 avatar gd-info">
		                          K
		                    </span>
                                            </a>
                                        </div>
                                        <div class="flex">
                                            <div class="item-feed h-2x">
                                                Prepare the documentation for the
                                                <a href='#'>Fitness app</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="list-item " data-id="2">
                                        <div>
                                            <a href="#">
                                                    <span class="w-32 avatar gd-primary">
		                          <img src="<?= $env->get("APP_URL") ?>/static/img/a2.jpg" alt=".">
		                    </span>
                                            </a>
                                        </div>
                                        <div class="flex">
                                            <div class="item-feed h-2x">
                                                Can data lead us to making the best possible decisions?
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex px-3 py-2 b-t">
                                <div class="flex">
                                    <span>6 Notifications</span>
                                </div>
                                <a href="page.setting.html">See all
                                    <i class="fa fa-angle-right text-muted"></i>
                                </a>
                            </div>
                        </div>
                        <!-- / dropdown -->
                    </li>
                    <!-- User dropdown menu -->
                    <li class="nav-item dropdown">
                        <a href="#" data-toggle="dropdown" class="nav-link d-flex align-items-center px-2 text-color">
                        <span class="avatar w-24" style="margin: -2px;"><img
                                    src="<?= $account->getProfileImage() ?>"
                                    alt="<?= $account->getFullName() ?>"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right w mt-3 animate fadeIn">
                            <a class="dropdown-item" href="page.profile.html">
                                <span><?= $account->getFullName() ?></span>
                            </a>
                            <a class="dropdown-item" href="page.price.html">
                                <span class="badge bg-success text-uppercase">Upgrade</span>
                                <span>to Pro</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="page.profile.html">
                                <span>Profile</span>
                            </a>
                            <a class="dropdown-item d-flex" href="page.invoice.html">
                                <span class="flex">Invoice</span>
                                <span><b class="badge badge-pill gd-warning">5</b></span>
                            </a>
                            <a class="dropdown-item" href="page.faq.html">Need help?</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="page.setting.html">
                                <span>Account Settings</span>
                            </a>
                            <a class="dropdown-item" href="signin.html">Sign out</a>
                        </div>
                    </li>
                    <!-- Navarbar toggle btn -->
                    <li class="nav-item d-lg-none">
                        <a href="#" class="nav-link px-2" data-toggle="collapse" data-toggle-class
                           data-target="#navbarToggler">
                            <i data-feather="search"></i>
                        </a>
                    </li>
                    <li class="nav-item d-lg-none">
                        <a class="nav-link px-1" data-toggle="modal" data-target="#aside">
                            <i data-feather="menu"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        <!-- ############ Footer END-->
        <!-- ############ Content START-->
        <div id="content" class="flex ">
            <!-- ############ Main START-->
            <?php
            $route_file = $modules->getContent();
            if (not_empty($route_file) && file_exists($route_file)) require $route_file;
            else  require DIRNAME . "../../routes/errors/404-not-found.php";
            ?>
            <!-- ############ Main END-->
        </div>
        <!-- ############ Content END-->
        <!-- ############ Footer START-->
        <div id="footer" class="page-footer">
            <div class="d-flex p-3">
                <span class="text-sm text-muted flex"><?= $env->get("COPYRIGHT_TERMS") ?></span>
                <div class="text-sm text-muted">Version 1.1.2</div>
            </div>
        </div>
        <!-- ############ Footer END-->
    </div>
    <!-- build:js <?= $env->get("APP_URL") ?>/static/js/site.min.js -->
    <!-- jQuery -->
    <script src="<?= $env->get("APP_URL") ?>/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="<?= $env->get("APP_URL") ?>/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="<?= $env->get("APP_URL") ?>/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- ajax page -->
    <!--<script src="<?= $env->get("APP_URL") ?>/libs/pjax/pjax.min.js"></script>-->
    <!--<script src="<?= $env->get("APP_URL") ?>/static/js/ajax.js"></script>-->
    <!-- lazyload plugin -->
    <script src="<?= $env->get("APP_URL") ?>/static/js/lazyload.config.js"></script>
    <script src="<?= $env->get("APP_URL") ?>/static/js/lazyload.js"></script>
    <script src="<?= $env->get("APP_URL") ?>/static/js/plugin.js"></script>
    <!-- scrollreveal -->
    <script src="<?= $env->get("APP_URL") ?>/libs/scrollreveal/dist/scrollreveal.min.js"></script>
    <!-- feathericon -->
    <script src="<?= $env->get("APP_URL") ?>/libs/feather-icons/dist/feather.min.js"></script>
    <script src="<?= $env->get("APP_URL") ?>/static/js/plugins/feathericon.js"></script>
    <!-- theme -->
    <script src="<?= $env->get("APP_URL") ?>/static/js/theme.js"></script>
    <script src="<?= $env->get("APP_URL") ?>/static/js/utils.js"></script>

    <?php
    if (not_empty_bool($modules->getLoadScript())) {
        echo $static->js([
            "niceselect/jquery.nice-select.js",
            "dashboard/main.js",
            "dashboard/" . $modules->getLoadScript()
        ])->embed();
    } ?>

    <script>
        $(document).ready(function () {
            $('select').niceSelect();
        });
    </script>
    </body>
<?php } else { ?>
    <body>
    <div id="content">
        <!-- ############ Main START-->
        <?php
        $route_file = $modules->getContent();
        if (not_empty($route_file) && file_exists($route_file)) require $route_file;
        else  require DIRNAME . "../../routes/errors/404-not-found.php";
        ?>
        <!-- ############ Main END-->
    </div>
    </body>
<?php } ?>
<!-- endbuild -->
</html>