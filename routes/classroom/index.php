<?php if ($classroom->getIsWritable()) { ?>
    <div class="content-wrapper">
        <div class="post-new-widget">
            <div class="container">

                <div class="post-comment">

                    <div class="tab-post">
                        <a class="tab-item blue-border active">
                            <div class="tab-icon">
                                <i class="far fa-pencil"></i>
                            </div>
                            <div class="tab-text"><?= translate("Write a quick question") ?></div>
                        </a>
                        <a href="<?= $url->application("classroom-tools")->page("post/" . $classroom->getIdClassroom())->add(["classroom" => $classroom->getIdClassroom()], true)->output() ?>"
                           class="tab-item green-border">
                            <div class="tab-icon">
                                <i class="far fa-book-open"></i>
                            </div>
                            <div class="tab-text"><?= translate("Write a post") ?></div>
                        </a>
                    </div>

                    <form action="<?= $url->application("classroom-tools")->page("post/" . $classroom->getIdClassroom())->add(["format" => "quick", "hg" => md5(rand(1, 100))])->actualAsNext()->output() ?>"
                          method="POST">
                        <div class="post-textarea">
                            <input type="hidden" name="classroom" value="<?= $classroom->getIdClassroom() ?>"/>
                            <label>
                            <textarea name="post_content"
                                      placeholder="<?= translate("Type your question up to 260 characters here...") ?>"
                                      maxlength="260"></textarea>
                                <div class="post-submit">
                                    <button><?= translate("Publish") ?></button>
                                </div>
                            </label>
                        </div>
                    </form>


                </div>

            </div>
        </div>
    </div>
<?php } ?>


<div id="load-posts">

    <div id="posts-loader">
        <?php for ($i = 0; $i < 3; $i++) { ?>
            <div class="content-wrapper">
                <div class="post-new-widget">
                    <div class="container">
                        <svg
                                role="img"
                                width="476"
                                height="124"
                                aria-labelledby="loading-aria"
                                viewBox="0 0 476 124"
                                preserveAspectRatio="none"
                        >
                            <title id="loading-aria">Loading...</title>
                            <rect
                                    x="0"
                                    y="0"
                                    width="100%"
                                    height="100%"
                                    clip-path="url(#clip-path)"
                                    style='fill: url("#fill");'
                            ></rect>
                            <defs>
                                <clipPath id="clip-path">
                                    <rect x="48" y="8" rx="3" ry="3" width="88" height="6"/>
                                    <rect x="48" y="25" rx="3" ry="3" width="52" height="6"/>
                                    <rect x="4" y="76" rx="3" ry="3" width="410" height="6"/>
                                    <rect x="4" y="92" rx="3" ry="3" width="380" height="6"/>
                                    <rect x="4" y="108" rx="3" ry="3" width="178" height="6"/>
                                    <rect x="0" y="0" width="38" height="38"/>
                                    <rect x="6" y="56" rx="0" ry="0" width="413" height="1"/>
                                </clipPath>
                                <linearGradient id="fill">
                                    <stop
                                            offset="0.599964"
                                            stop-color="#f3f3f3"
                                            stop-opacity="1"
                                    >
                                        <animate
                                                attributeName="offset"
                                                values="-2; -2; 1"
                                                keyTimes="0; 0.25; 1"
                                                dur="2s"
                                                repeatCount="indefinite"
                                        ></animate>
                                    </stop>
                                    <stop
                                            offset="1.59996"
                                            stop-color="#ecebeb"
                                            stop-opacity="1"
                                    >
                                        <animate
                                                attributeName="offset"
                                                values="-1; -1; 2"
                                                keyTimes="0; 0.25; 1"
                                                dur="2s"
                                                repeatCount="indefinite"
                                        ></animate>
                                    </stop>
                                    <stop
                                            offset="2.59996"
                                            stop-color="#f3f3f3"
                                            stop-opacity="1"
                                    >
                                        <animate
                                                attributeName="offset"
                                                values="0; 0; 3"
                                                keyTimes="0; 0.25; 1"
                                                dur="2s"
                                                repeatCount="indefinite"
                                        ></animate>
                                    </stop>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>

</div>


<script type="text/javascript">
    let feed = new Feed();


    <?php if($classroom->getIdClassroom() == 5) { ?>
    feed.index();
    <?php }else{ ?>
    feed.get(<?=$classroom->getIdClassroom()?>);
    <?php } ?>




    <?php if(not_empty_bool(get_request("highlight"))){ ?>
    function highlight() {
        let h = document.getElementById("P<?=get_request("highlight")?>");
        if (h !== null && h !== undefined) {
            h.className = "post-new-widget highlight";
            h.setAttribute("data-highlight", "<?=translate("this post is in active focus")?>")
        }
    }

    window.setInterval(function () {
        highlight();
    }, 1000);
    <?php } ?>
</script>
