<?php
echo $OUTPUT->doctype();
?>
<html class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)); ?>" <?php echo $OUTPUT->htmlattributes() ?> >
<head>
    <?php 
    // IE 9 workaround for Flash bug: MDL-29213
    // - Something like this is added to mod/scorm/player.php, but the tag doesn't get added until too late
    // adding it here works better: DPMH
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9') !== false) { ?>
         <meta http-equiv="X-UA-Compatible" content="IE=7" />
    <?php } ?>
    <meta charset="utf-8">
    <title><?php echo $PAGE->title . ' | ' . $SITE->fullname . ' on Learning Pool'; ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
</head>
<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)) ?>">
<?php echo $OUTPUT->standard_top_of_body_html() ?>
    <div id="page">
        <div id="page-content">
            <div id="region-main-box">
                <div id="region-main-wrap">
                    <div id="region-main">
                        <div class="region-content">
                            <?php echo core_renderer::MAIN_CONTENT_TOKEN ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="vc"></div>
    </div>
    <?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>
