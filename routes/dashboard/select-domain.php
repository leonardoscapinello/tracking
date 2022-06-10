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
                    <span class="d-none d-sm-inline mx-1"><?= translate("Track a new domain") ?></span>
                </a>
            </div>
        </div>
    </div>

    <div class="page-content page-container" id="page-content">

        <?php if(count($domains->getAllDomains()) < 1){ ?>

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
                        <p><?= translate("Aww yeah, you successfully created your account and the next step is register a new domain to start track your data.") ?></p>
                        <a href="<?= $url->application("dashboard")->page("new-domain")->output() ?>" class="btn btn-white mx-1"><?= translate("Track my first domain") ?>
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

        <?php }else{ ?>

        <div id="invoice-list">

            <div class="table-responsive">
                <table class="table table-theme table-row v-middle">

                    <tbody class="list">
                    <tr class=" v-middle" data-id="3">
                        <td>
                            <label class="ui-check m-0 ">
                                <input type="checkbox" name="id" value="3">
                                <i></i>
                            </label>
                        </td>
                        <td style="min-width:30px;text-align:center">
                            <small class="text-muted">3</small>
                        </td>
                        <td class="flex">
                            <a href="page.invoice.detail.html" class="item-company ajax h-1x" data-pjax-state="">
                                Wealth corp
                            </a>
                            <div class="item-mail text-muted h-1x d-none d-sm-block">
                                jordan-stephens@wealthcorp.com
                            </div>
                        </td>
                        <td>
                                                    <span class="item-amount d-none d-sm-block text-sm ">
                        10
                      </span>
                        </td>
                        <td>
                                                    <span class="item-badge badge text-uppercase   bg-primary ">
                          Billing
                      </span>
                        </td>
                        <td class="no-wrap">
                            <div class="item-date text-muted text-sm d-none d-md-block">1 hour ago</div>
                        </td>
                        <td>
                            <div class="item-action dropdown">
                                <a href="#" data-toggle="dropdown" class="text-muted">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                         stroke-linecap="round" stroke-linejoin="round"
                                         class="feather feather-more-vertical">
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="12" cy="5" r="1"></circle>
                                        <circle cx="12" cy="19" r="1"></circle>
                                    </svg>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right bg-black" role="menu">
                                    <a class="dropdown-item" href="#">
                                        See detail
                                    </a>
                                    <a class="dropdown-item download">
                                        Download
                                    </a>
                                    <a class="dropdown-item edit">
                                        Edit
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item trash">
                                        Delete item
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <?php } ?>

    </div>

</div>
