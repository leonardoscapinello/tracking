<?php
$register_class = "";
$validation_class = "active";
if (get_request("action") === "setup") {

}


?>

<div>
    <div class="page-hero page-container " id="page-hero">
        <div class="padding d-flex">
            <div class="page-title">
                <h2 class="text-md text-highlight"><?= translate($modules->getTitle()) ?></h2>
            </div>
            <div class="flex"></div>
        </div>
    </div>
</div>


<form action="" accept-charset="utf-8" method="GET">
    <?= $fields->hidden()->name("action")->id("action")->value("register")->output() ?>
    <div class="page-content page-container" id="page-content">
        <div class="padding">
            <div class="card">
                <div class="card-header">
                    <strong><?= translate($modules->getTitle()) ?></strong>
                </div>
                <div class="card-body">
                    <div id="rootwizard">
                        <ul class="nav mb-3">
                            <li class="nav-item">
                                <a class="nav-link text-center <?= $register_class ?>">
                                    <span class="w-32 d-inline-flex align-items-center justify-content-center circle bg-light active-bg-success">1</span>
                                    <div class="mt-2">
                                        <div class="text-muted"><?= translate("Domain") ?></div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-center <?= $validation_class ?>">
                                    <span class="w-32 d-inline-flex align-items-center justify-content-center circle bg-light active-bg-success disabled">2</span>
                                    <div class="mt-2">
                                        <div class="text-muted"><?= translate("Verification") ?></div>
                                    </div>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-center">
                                    <span class="w-32 d-inline-flex align-items-center justify-content-center circle bg-light active-bg-success">3</span>
                                    <div class="mt-2">
                                        <div class="text-muted"><?= translate("Tracking") ?></div>
                                    </div>
                                </a>
                            </li>
                        </ul>


                        <input type="hidden" value="register" name="stage">
                        <div class="tab-content p-3">
                            <div class="tab-pane active" id="tab1">


                                <?= $fields->text()->label("Domain")->classList("form-control")->readonly()->value($domains->getDomain())->name("domain")->id("domain")->output() ?>
                                <?= $fields->select()->name("validation_type")->label("Select a validation method")->options([
                                    "dns" => "Add a DNS record to your DNS Zone",
                                    "meta" => "Meta tag on head",
                                    "html" => "Upload HTML file into server",
                                ])->value($domains->getVerifyMethod())->customScript("onClick='setVerifyMethod(this.value);return false'")->output() ?>


                            </div>

                            <div class="row py-3">
                                <div class="col-12">
                                    <div class="d-flex justify-content-center">
                                        <button class="btn btn-white button-next">
                                            <?= translate("Continue") ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                 viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                 stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-arrow-right ml-2">
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                                <polyline points="12 5 19 12 12 19"></polyline>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
