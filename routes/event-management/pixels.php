<div>
    <div class="page-hero page-container " id="page-hero">
        <div class="padding d-flex">
            <div class="page-title">
                <h2 class="text-md text-highlight"><?= translate($modules->getTitle()) ?></h2>
            </div>
            <div class="flex"></div>
            <div>
                <a href="<?= $url->application("dashboard")->page("new-domain")->output() ?>"
                   class="btn btn-md text-muted">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-plus mx-2">
                        <line x1="12" y1="5" x2="12" y2="19"></line>
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span class="d-none d-sm-inline mx-1"><?= translate("Create a new pixel") ?></span>
                </a>
            </div>
        </div>
    </div>
    <div class="page-content page-container" id="page-content">
        <div class="col-12 p-0  ml-0">
            <div class="alert bg-success p-4" role="alert">
                <div class="d-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                         class="feather feather-check-circle">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <div class="px-3">
                        <h5 class="alert-heading"><?= translate("Welcome, %s.", $account->getFirstName()) ?></h5>
                        <p><?= translate("Aww yeah, you successfully created your account and the next step is register a new pixel to start track your data.") ?></p>
                        <a href="<?= $url->application("dashboard")->page("new-pixel")->output() ?>"
                           class="btn btn-white mx-1"><?= translate("Create my first pixel") ?>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round" class="feather feather-arrow-right ml-2">
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