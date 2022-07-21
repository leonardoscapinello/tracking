<?php
$pixels = new Pixels();
$badge = new Badge();
$list = $pixels->getAll();

if (not_empty_bool(get_request("cr"))) {
    $st = $pixels->cancel_disable(get_request("cr"));
    if ($st) $url->actualPage()->remove(["cr"])->redirect();
}

?>
<div class="card">
    <div class="card-body">
        <?php if (count($list) <= 0){ ?>
            <div class="rightMost text-right block mb-10" style="display:block;text-align: right;">
                <a href="<?= $url->application("dashboard")->page("new-pixel")->output() ?>" class="btn btn-light">
                    <?= translate("New Pixel") ?>
                </a>
            </div>
            <div class="d-flex align-items-center rounded py-5 px-5 bg-light-primary ">
                <span class="svg-icon svg-icon-3x svg-icon-primary me-5">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <rect opacity="0.3" x="2" y="2" width="20" height="20" rx="10"
                              fill="currentColor"></rect>
                        <rect x="11" y="14" width="7" height="2" rx="1"
                              transform="rotate(-90 11 14)" fill="currentColor"></rect>
                        <rect x="11" y="17" width="2" height="2" rx="1"
                              transform="rotate(-90 11 17)" fill="currentColor"></rect>
                    </svg>
                </span>
                <div class="text-gray-700 fw-bold fs-6">
                    <?= translate("No pixels found, configure your first pixel to start measuring hits.") ?>
                </div>
            </div>
        <?php }else{ ?>
        <div class="table-responsive">

            <div class="rightMost text-right block" style="display:block;text-align: right;">
                <a href="<?= $url->application("dashboard")->page("new-pixel")->output() ?>" class="btn btn-light">
                    <?= translate("New Pixel") ?>
                </a>
            </div>
            <table class="table table-striped gy-7 gs-7">
                <thead>
                <tr class="fw-semibold fs-6 text-gray-800 border-bottom-2 border-gray-200">
                    <th class=""><?= translate("Pixel ID") ?></th>
                    <th class="min-w-200px"><?= translate("Pixel Name") ?></th>
                    <th class="min-w-200px"><?= translate("Pixel Token") ?></th>
                    <th class=""><?= translate("Status") ?></th>
                    <th class=""><?= translate("Last Activity date") ?></th>
                    <th class="max-w-200px"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                for ($i = 0; $i < count($list); $i++) {
                    if ($list[$i]['is_active'] === "Y") $pixel_url = $url->application("dashboard")->page("edit-pixel")->setId($list[$i]['pixel_token'])->output();
                    else $pixel_url = $url->application("dashboard")->page("pixels")->add(["tokne" => $text->random(64)->output(), "cr" => $list[$i]['pixel_token']])->output();

                    $remove_date = $date->set($list[$i]['remove_date'])->plusHours(REMOVAL_DELAY)->format("Y-m-d H:i:s")->output();
                    ?>
                    <tr>
                        <td>#<?= $numeric->set($list[$i]['id_pixel'])->zeroFill(4)->output() ?></td>
                        <td><?= $list[$i]['pixel_name'] ?></td>
                        <td class="text-muted"><?= $list[$i]['pixel_token'] ?></td>
                        <td><?= $list[$i]['is_active'] === "Y" ? $badge->success()->text("Active")->output() : $badge->danger()->text("Waiting Removal")->output() ?></td>
                        <td><?= not_empty_bool($list[$i]['activity_time']) ? $date->set($list[$i]['activity_time'])->format()->output() : translate("-") ?></td>
                        <td class="text-align-left" style="text-align: left">
                            <a href="<?= $pixel_url ?>"
                               class="btn btn-link btn-color-success btn-active-color-primary p-0"
                               style="text-align: left">
                                <?php if ($list[$i]['is_active'] === "Y") { ?>
                                    <i class="fa-duotone fa-pen"
                                       style="font-size: 16px;"></i> <?= translate("Settings") ?>
                                <?php } else { ?>
                                    <i class="fa-duotone fa-times"
                                       style="font-size: 16px;"></i> <?= translate("Cancel deletion") ?>
                                    <div class="text-muted small"
                                         style="max-width: 200px"><?= translate("%s before removal", $date->getRemainingTimeFromNow($remove_date)) ?></div>
                                <?php } ?>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php } ?>
