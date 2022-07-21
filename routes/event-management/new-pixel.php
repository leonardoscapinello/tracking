<?php
$action = get_request("action");
if ("save" === $action) {
    $pixels = new Pixels();
    $status = $pixels->setPixelName(get_request("pixel_name"))->save();
    if (not_empty_bool($status)) $url->application("dashboard")->page("pixels")->setId($status)->redirect();
}
?>
<form accept-charset="utf-8" method="POST" action="">
    <?= $fields->hidden()->name("action")->value("save")->output() ?>
    <div style="max-width: 820px;margin: 0 auto">


        <div class="page-content page-container" id="page-content">
            <div class="padding">
                <div class="card">
                    <div class="card-body">

                        <?= $alerts->warning()->text("James will monitor every page you implement the trache script. Under Pixel Settings, you'll see the embed code to implement and monitor each event specifically.")->display() ?>

                        <div style="height: 20px;"></div>

                        <?= $fields->text()->label("Domain")->classList("form-control")->markdown("This pixel should only be deployed in this domain")->readonly()->value($domains->getDomain())->name("domain")->id("domain")->output() ?>
                        <?= $fields->text()->label("Pixel name")->classList("form-control")->value(translate("%s's Pixel", $account->getCompanyName()))->markdown("The pixel name will be used to identify it, but it has no relevance to tracing")->required()->name("pixel_name")->id("pixel_name")->output() ?>

                        <div class="center text-center">
                            <button id="submit" class="btn btn-success"><?= translate("Create a new pixel") ?></button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<style>
    .dotted-arrow {
        position: relative;
    }

    .dotted-arrow:before {
        content: "........";
        white-space: nowrap;
        font-size: 36px;
        line-height: 36px;
        width: 50px;
        position: absolute;
        top: -18px;
        left: -62px;
        overflow: hidden;
        opacity: .2;
    }

</style>