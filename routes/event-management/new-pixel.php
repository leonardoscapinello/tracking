<form>
    <div style="max-width: 820px;margin: 0 auto">

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

        <div class="page-content page-container" id="page-content">
            <div class="padding">
                <div class="card">
                    <div class="card-header">
                        <strong><?= translate($modules->getTitle()) ?></strong>
                    </div>
                    <div class="card-body">
                        <?= $fields->text()->label("Domain")->classList("form-control")->readonly()->value($domains->getDomain())->name("domain")->id("domain")->output() ?>
                        <?= $fields->text()->label("Pixel name")->classList("form-control")->value("")->name("pixel_name")->id("pixel_name")->output() ?>

                        <div class="card-header">
                            <strong><?= translate("Tracking events") ?></strong>
                        </div>

                        <?= $alerts->warning()->text("James will monitor every page on your site once you add the code to the head. In the pixel settings you must define which pages consider each event.")->display() ?>

                        <div style="height: 20px"></div>

                        <div class="row align-content-center center-layout">
                            <div class="col-sm-6">
                                <i class="fa-duotone fa-fire"></i>
                                <?= translate("Initial event") ?>
                            </div>
                            <div class="col-sm-6">
                                <i class="fa-duotone fa-bullseye"></i>
                                <?= translate("Conclusion event") ?>
                            </div>
                        </div>

                        <div style="height: 20px"></div>
                        <div class="row align-content-center center-layout">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="md-switch">
                                        <input type="checkbox" name="events[]" value="PageView">
                                        <i class="green"></i>
                                        <?= translate("Page view") ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6 dotted-arrow">
                                <div class="mb-3">
                                    <label class="md-switch">
                                        <input type="checkbox" name="events[]" value="PageLoad">
                                        <i class="green"></i>
                                        <?= translate("Page finished load") ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3 align-content-center center-layout">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="md-switch">
                                        <input type="checkbox" name="events[]" value="InitiateCheckout">
                                        <i class="green"></i>
                                        <?= translate("Access to checkout") ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6 dotted-arrow">
                                <div class="mb-3">
                                    <label class="md-switch">
                                        <input type="checkbox" name="events[]" value="Purchase">
                                        <i class="green"></i>
                                        <?= translate("Purchase completed") ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3 align-content-center center-layout">
                            <div class="col-sm-6">
                                <div class="mb-3">
                                    <label class="md-switch">
                                        <input type="checkbox" name="events[]" value="RegisterCompleted">
                                        <i class="green"></i>
                                        <?= translate("Register completed") ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6  dotted-arrow">
                                <div class="mb-3">
                                    <label class="md-switch">
                                        <input type="checkbox" name="events[]" value="Lead">
                                        <i class="green"></i>
                                        <?= translate("Lead") ?>
                                    </label>
                                </div>
                            </div>
                        </div>


                        <button id="submit" class="btn btn-success"><?= translate("Create a new pixel") ?></button>

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