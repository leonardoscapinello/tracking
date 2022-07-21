<?php
$register_class = "";
$validation_class = "active";
$error = "";


if ($domains->getIsVerified()) {
    $url->application("dashboard")->page("dashboard")->redirect();
    die;
}

if (get_request("action") === "verify-domain") {
    if (!$domains->validateDomain()) {
        $url->actualPage()->remove("action")->add(["status" => "not-propagated"])->redirect();
    }
}

if ($url->getId() !== $domains->getPublicKey()) {
    $url->application("dashboard")->page("setup-domain")->setId($domains->getPublicKey())->redirect();
}

if (not_empty(get_request("method"))) {
    $method_updated = $domains->updateValidationMethod(get_request("method"), $domains->getPublicKey());
    if ($method_updated) $url->actualPage()->remove("method")->redirect();
}

if (not_empty(get_request("status")) === "not-propagated") {
    $validation_class = "danger";
}

?>

<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="card">

            <div class="card-body">


                <ul class="rootwizard">
                    <li class="nav-item">
                        <a class="nav-link text-center <?= $register_class ?>">
                            <span class="circle bg-light active-bg-success">1</span>
                            <div class="mt-2">
                                <div class="text-muted"><?= translate("Domain") ?></div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-center <?= $validation_class ?>">
                            <span class="circle bg-light active-bg-success disabled">2</span>
                            <div class="mt-2">
                                <div class="text-muted"><?= translate("Verification") ?></div>
                            </div>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-center">
                            <span class="circle bg-light">3</span>
                            <div class="mt-2">
                                <div class="text-muted"><?= translate("Events") ?></div>
                            </div>
                        </a>
                    </li>
                </ul>


                <input type="hidden" value="register" name="stage">
                <div class="tab-content p-3">
                    <div class="tab-pane active" id="tab1">

                        <?php if (get_request("status") === "not-propagated") { ?>
                            <div class="alert alert-danger" role="alert">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round"
                                     class="feather feather-info">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="16" x2="12" y2="12"></line>
                                    <line x1="12" y1="8" x2="12" y2="8"></line>
                                </svg>
                                <span class="mx-2">
                                    <?= translate("The domain %s cannot be validated, some methods can take up to 72 hours to propagate across the network.", $domains->getDomain()) ?>
                                </span>
                            </div>
                        <?php } ?>

                        <?= $fields->text()->label("Domain")->classList("form-control")->readonly()->value($domains->getDomain())->name("domain")->id("domain")->output() ?>
                        <?= $fields->select()->name("validation_type")->label("Select a validation method")->value($domains->getValidationMethod())->options([
                            "dns" => "Update DNS TXT record with your domain",
                            "meta" => "Add a meta tag to your HTML source code",
                            "html" => "Upload an HTML file in your root directory",
                        ])->customScript("onchange='setVerifyMethod(this.value);return false'")->classList("wide")->output() ?>
                    </div>

                    <?php if ("meta" === $domains->getValidationMethod()) { ?>
                        <div class="dns-step-block">
                            <span class="dns-line"><?= translate("Copy this meta-tag:") ?></span>
                            <span class="dns-line strong"><?= htmlentities('<meta name="' . $domains->getVerificationMetaName() . '" content="' . $domains->getVerificationKey() . '" />') ?></span>
                        </div>
                        <div class="dns-step-block">
                            <span class="dns-line"><?= translate("Paste the meta-tag into the <head> ... <head> section of the website’s home page HTML source, and publish the page.") ?></span>
                            <span class="dns-line strong small"><?= translate("Note: Verification will fail if the meta tag code is outside the <head> section or in a section dynamically loaded by JavaScript.") ?></span>
                        </div>
                        <div class="dns-step-block">
                            <span class="dns-line"><?= translate("Once you have published the homepage, confirm that the meta tag is visible by going to %s and checking the HTML source code.", $domains->getDomain()) ?></span>
                        </div>
                        <div class="dns-step-block">
                            <span class="dns-line"><?= translate("Click in Verify Domain") ?></span>
                            <span class="dns-line strong small"><?= translate("Note: Nota: It may take up to 72 hours for Facebook to find the meta-tag code. If the domain status is still not verified, you’ll need to click Verificar domínio again or confirm the meta-tag is listed") ?></span>
                        </div>
                    <?php } elseif ("dns" === $domains->getValidationMethod()) { ?>

                        <div class="dns-step-block">
                            <span class="dns-line"><?= translate("Go to your domain register, log into your account and find the DNS records section.") ?></span>
                        </div>
                        <div class="dns-step-block">
                            <span class="dns-line"><?= translate("Follow the instructions to add this TXT record to your DNS configuration:") ?></span>
                            <div class="fields-view">
                                <span class="dns-fields" data-label="<?= translate("Type:") ?>">TXT</span>
                                <span class="dns-fields" data-label="<?= translate("Host:") ?>">@</span>
                                <span class="dns-fields"
                                      data-label="<?= translate("Value:") ?>"><?= $domains->getVerificationMetaName() ?>=<?= $domains->getVerificationKey() ?></span>
                                <span class="dns-fields" data-label="<?= translate("TTL:") ?>">3600</span>
                            </div>
                        </div>
                        <div class="dns-step-block">
                            <span class="dns-line"><?= translate("Use a DNS TXT lookup tool to confirm the record has been updated on your servers before clicking Verify Domain.") ?></span>
                        </div>
                        <div class="dns-step-block">
                            <span class="dns-line"><?= translate("Click Verify Domain") ?></span>
                            <span class="dns-line strong small"><?= translate("Note: It can take up to 72 hours for the change to propagate to your servers. If the domain status is still Unverified, you will need to click Verify Domain again.") ?></span>
                        </div>

                    <?php } elseif ("html" === $domains->getValidationMethod()) { ?>
                        <div class="dns-step-block">
                            <span class="dns-line"><?= translate("Download the HTML verification file. Do not modify this file after downloading it.") ?></span>
                            <span class="dns-line mt-2 d-flex justify-content-center"><a
                                        href="<?= $url->application("download-domain-file")->page($domains->getPublicKey())->output() ?>"
                                        target="_blank"
                                        class="btn mb-1 btn-wave"><?= translate("Download verification file") ?></a></span>
                        </div>
                        <div class="dns-step-block">
                            <span class="dns-line"><?= translate("Upload the file to the domain root directory: %s", "https://" . $domains->getDomain()) ?></span>
                            <span class="dns-line small strong"><?= translate("Usually the site root is named public_html or www") ?></span>
                        </div>
                        <div class="dns-step-block">
                            <span class="dns-line"><?= translate("You will know that the HTML verification file has been successfully uploaded if the verification code appears on your website: %s", "https://" . $domains->getDomain() . "/" . $domains->getVerificationKey() . ".html") ?></span>
                            <span class="dns-line mt-2 d-flex justify-content-center"><a
                                        href="<?= "https://" . $domains->getDomain() . "/" . $domains->getVerificationKey() . ".html" ?>"
                                        class="btn mb-1 btn-wave"
                                        target="_blank"><?= translate("Access destination path") ?></a></span>
                        </div>
                    <?php } ?>


                    <div class="row py-3">
                        <div class="col-12">
                            <div class="d-flex justify-content-center">
                                <a class="btn btn-success button-next"
                                   href="<?= $url->actualPage()->add(["action" => "verify-domain"])->output() ?>">
                                    <?= translate("Verify Domain") ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-arrow-right ml-2">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
