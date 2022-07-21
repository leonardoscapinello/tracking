<?php
$pixels = new Pixels($url->getId());
if (!not_empty_bool($pixels->getPixelToken())) {
    $url->application("dashboard")->page("pixels")->redirect();
}

if($pixels->getIsActive() !== "Y"){
    $url->application("dashboard")->page("pixels")->redirect();
}

if(get_request("remove") === "Y"){
    $removed = $pixels->disable();
    if($removed)  $url->actualPage()->remove("remove")->redirect();
}

?>

<?php
$events = new PixelsEvents();
$list = $events->get();
for ($i = 0; $i < count($list); $i++) {
    $script = $pixels->getTrackFunctionName() . "('" . $list[$i]['event_key'] . "', '" . $pixels->getPixelToken() . "'" . (not_empty_bool($list[$i]['object_example']) ? ", " . $list[$i]['object_example'] . ")" : ")");

    ?>

    <?php if ($i % 2 === 0) { ?>
        <div class="row">
    <?php } ?>
    <div class="col-12 col-sm-12 col-md-12 col-xl-6 col-lg-6 mt-5 mb-5">

        <div class="card shadow-sm">
            <div class="card-header">
                <h3 class="card-title"><?= translate($list[$i]['event_name']) ?></h3>

                <?php if ($list[$i]['is_notifiable'] === "Y") { ?>
                    <div class="form-check form-switch form-check-custom form-check-solid me-10">
                        <input class="form-check-input h-20px w-30px" type="checkbox" value="Y"
                               id="Notify<?= $list[$i]['event_key'] ?>" name="Notify<?= $list[$i]['event_key'] ?>"/>
                        <label class="form-check-label text-muted opacity-50" for="Purchase">
                            <?= translate("Notify me on whatsapp") ?>
                        </label>
                    </div>
                <?php } ?>

            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-12 col-xl-4">
                        <?= translate($list[$i]['event_description']) ?>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-12 col-xl-8 code-margin">
                            <pre class="p-3">
                                <?= $script ?>
                            </pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if ($i % 2 === 1 || (count($list) - 1) === $i) { ?>
        </div>
    <?php } ?>
<?php } ?>

<div style="height: 50px;"></div>

<div class="card shadow-sm p-0 m-0">
    <div class="alert alert-dismissible bg-light-danger m-0 d-flex flex-column ">
        <div class="row align-content-center center-container p-4">

            <div class="col-sm-12 col-md-12 col-lg-1 col-xl-1 mobile-center">

               <span class="svg-icon svg-icon-5tx svg-icon-danger">
                   <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                       <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10" fill="currentColor"></rect>
                       <rect x="11" y="14" width="7" height="2" rx="1" transform="rotate(-90 11 14)"
                             fill="currentColor"></rect>
                       <rect x="11" y="17" width="2" height="2" rx="1" transform="rotate(-90 11 17)"
                             fill="currentColor"></rect>
                   </svg>
               </span>

            </div>

            <div class="col-sm-12 col-md-12 col-lg-6 col-xl-6 mobile-center">
                <h3 class="fw-bold mb-5"><?= translate("Delete this pixel") ?></h3>
                <div>
                    <?= translate("You can remove this pixel and all data related to it, but this action is irreversible, after removal it will not be possible to recover any information.") ?>
                </div>
            </div>
            <div class="col-sm-0 col-md-0 col-lg-3 col-xl-3"></div>
            <div class="col-sm-12 col-md-12 col-lg-2 col-xl-2 right mobile-center mt-2">
                <a href="#" class="btn btn-danger m-2" data-bs-toggle="modal" data-bs-target="#kt_modal_1"><?= translate("Yes, remove it!") ?></a>
            </div>

        </div>

    </div>
</div>

<div class="modal fade" tabindex="-1" id="kt_modal_1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><?=translate("Are you sure?")?></h3>

                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                    <span class="svg-icon svg-icon-1"></span>
                </div>
                <!--end::Close-->
            </div>

            <div class="modal-body">
                <?= translate("You can remove this pixel and all data related to it, but this action is irreversible, after removal it will not be possible to recover any information.") ?>
            </div>

            <div class="modal-footer">
                <a class="btn btn-light" data-bs-dismiss="modal"><?=translate("Cancel")?></a>
                <a href="<?= $url->actualPage()->add(["remove" => "Y"])->output() ?>" class="btn btn-danger"><?=translate("Yes, I'm sure")?></a>
            </div>
        </div>
    </div>
</div>